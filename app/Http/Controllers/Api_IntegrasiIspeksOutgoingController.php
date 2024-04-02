<?php

namespace App\Http\Controllers;

use Storage;
use Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Controllers\EmailNotificationController;
use App\Models\Api\Api_IspeksIntegration;
use App\Models\Api\Api_CollectorStatement;
use App\Models\Api\Api_CollectorStatementLog;
use App\Services\GPG;
use App\Services\ValidateResitPerbendaharaan;
use App\Services\ValidateBatalJurnal;

class Api_IntegrasiIspeksOutgoingController extends Controller
{

    public $time_stamp, $tahun, $bulan;
    public $kod_negeri, $kod_negeri_detail, $kod_agensi, $kod_jabatan, $kod_ptj;
    public $kod_swift_bank, $no_akaun_bank;

    public function process_outgoing(){

        $appEnv = config('env.app_env');

        $response_rsp = 0;
        $response_jurnal = 0;

        if($appEnv == "local"){
            $directory = "gpg/";//DEV
            $files = Storage::disk('ispeks-doc-out')->files($directory);//DEV
        }else{
            $directory = config('services.ispeks.folder_out');//PROD
            $files = Storage::disk('ftp-ispeks-doc')->files($directory);//PROD
        }

        //FILE LIST IN DIRECTORY
        if($files){
            foreach($files as $file) {

                $nama_fail_gpg = Str::replace($directory, '', $file);
                $arr_nama_fail_gpg = explode('_', $nama_fail_gpg);
                $kod_fail_gpg = $arr_nama_fail_gpg[2];

                //$nama_fail_gpg="01_00034_RSP_20230604173736.gpg";
                //$nama_fail_txt="01_00034_RSP_20230604173736.txt";
                $kod_fail_gpg="RSP";

                if($kod_fail_gpg == 'RSP' || $kod_fail_gpg == 'BPJ'){

                    if($appEnv != "local"){
                        //COPY & PASTE FILE FROM SFTP DIRECTORY
                        $data_gpg = Storage::disk('ftp-ispeks-doc')->get($directory.$nama_fail_gpg);
                        Storage::disk('ispeks-doc-out')->put($nama_fail_gpg, $data_gpg);
                        //DELETE FILE FROM SFTP DIRECTORY 
                        Storage::disk('ftp-ispeks-doc')->delete(config('services.ispeks.folder_out').$nama_fail_gpg);
                    }

                    //DECRYPT FILE
                    $nama_fail_txt = (new GPG())->file_decryption_gnupg($nama_fail_gpg);
                    //DELETE GPG FILE IN LOCAL DIRECTORY AFTER DECRYPTION
                    //File::delete(config('services.ispeks.local_folder_out').$nama_fail_gpg);

                    if($nama_fail_txt){
                        //RESIT PERBENDAHARAAN
                        if($kod_fail_gpg == 'RSP'){
                            $this->process_fail_resit_perbendaharaan($nama_fail_gpg, $nama_fail_txt, $kod_fail_gpg);
                            $response_rsp++;
                        }
                    }
                }
            }

            return response()->json(['response_rsp' => $response_rsp, 'response_jurnal' => $response_jurnal]);
        }
    }

    public function process_fail_resit_perbendaharaan($nama_fail_gpg, $nama_fail_txt, $kategori){

        //TXT FILE VALIDATION & DATA PROCESSING IN LOCAL DIRECTORY
        $errorDataCounter = array();
        $errorMsgContent = array();

        Session::forget('errorDataCounter');
        Session::forget('errorMsgContent');
        Session::forget('ispeks_integration_id');

        File::lines(config('services.ispeks.local_folder_out').'txt/'.$nama_fail_txt)->each(function ($line, $line_index) use($nama_fail_gpg, $nama_fail_txt, $kategori, $errorMsgContent, $errorDataCounter) {
            //dd($line);
            $totalLineColumn = "";
            $LineArr = explode('|', $line);
            $dataResitPerbendaharaanArr = array_filter($LineArr, function($value) {
                return $value != null;
            });

            // if($line_index==0 || $line_index==1){
            //     $totalLineColumn = ($line_index == 0) ?  3 : 11;
            //     $errorDataCounter = (new ValidateResitPerbendaharaan())->validateDataCounter($line, $dataResitPerbendaharaanArr, $totalLineColumn);
            //     ($errorDataCounter) ? session()->push('errorDataCounter', $errorDataCounter) : [];

            //     $errorMsgContent = (new ValidateResitPerbendaharaan())->validateLine($line, $line_index, $dataResitPerbendaharaanArr);
            //     (isset($errorMsgContent)) ? session()->push('errorMsgContent', $errorMsgContent) : [];
            // }

            //FILTER EMPTY NESTED ARRAY
            //$errorMsgContent = array_filter(array_map('array_filter', $errorMsgContent));
            //dd($errorMsgContent);
            //IF FILE VALIDATED SUCCESS
            //if(!$errorMsgContent){

                if($line_index == 0){
                    //SAVE DATA INTEGRASI
                    $ispeks_integration_id = $this->save_data_integrasi($nama_fail_gpg, $nama_fail_txt, $kategori);
                    ($ispeks_integration_id) ? Session::put('ispeks_integration_id', $ispeks_integration_id) : 0;
                }

                if($line_index > 0){
                    //dd($dataResitPerbendaharaanArr);
                    $this->update_penyata_pemungut($dataResitPerbendaharaanArr, Session::get('ispeks_integration_id'));
                }

            //}

        });

        //GET SESSION
        // $errorCounter = (Session::get('errorDataCounter')) ?  Session::get('errorDataCounter') : []; // DONE
        // $errorContent = (Session::get('errorMsgContent')) ?  Session::get('errorMsgContent') : []; // DONE
        // $errorCounter = array_filter(array_map('array_filter', $errorCounter));
        // $errorContent = array_filter(array_map('array_filter', $errorContent));
        // //SEND EMAIL NOTIFICATION
        // //FILTER EMPTY NESTED ARRAY
        // if(!empty($errorCounter) && !empty($errorContent)){
        //     (new EmailNotificationController())->fail_ditolak_di_sistem_agensi(Session::get('ispeks_integration_id'), $errorCounter, $errorContent);
        // }

    }

    //SAVE DATA INTEGRASI
    public static function save_data_integrasi($nama_fail_gpg, $nama_fail_txt, $kategori){

        $dataIntegrasiIspeks = new Api_IspeksIntegration;
        $dataIntegrasiIspeks->file_name_gpg = $nama_fail_gpg;
        $dataIntegrasiIspeks->file_name = $nama_fail_txt;
        $dataIntegrasiIspeks->category = $kategori;
        $dataIntegrasiIspeks->process_type = "OUT";
        $dataIntegrasiIspeks->action_on = currentDate();
        $dataIntegrasiIspeks->save();

        return $dataIntegrasiIspeks->id;

    }

    //UPDATE DATA PENYATA PEMUNGUT
    public static function update_penyata_pemungut($dataResitPerbendaharaanArr, $ispeks_integration_id){

        //dd($dataResitPerbendaharaanArr);
        $no_resit_perbendaharaan = "";
        $tarikh_resit_perbendaharaan_temp = "";
        $no_penyata_pemungut = "";
        //$tarikh_penyata_pemungut_temp = "";
        $tarikh_kelulusan_temp = "";

        //[3] - No. Resit Perbendaharaan
        if(isset($dataResitPerbendaharaanArr[3]))$no_resit_perbendaharaan = removeStringPadding($dataResitPerbendaharaanArr[3], " ", "R");
        //[4] - Tarikh Resit Perbendaharaan
        if(isset($dataResitPerbendaharaanArr[4]))$tarikh_resit_perbendaharaan_temp = removeStringPadding($dataResitPerbendaharaanArr[4], " ", "R");
        //[5] - No. Penyata Pemungut
        if(isset($dataResitPerbendaharaanArr[5]))$no_penyata_pemungut = removeStringPadding($dataResitPerbendaharaanArr[5], " ", "R");
        //[6] - Tarikh Penyata Pemungut
        //if(isset($dataResitPerbendaharaanArr[6]))$tarikh_penyata_pemungut_temp = removeStringPadding($dataResitPerbendaharaanArr[6], " ", "R");
        if(isset($dataResitPerbendaharaanArr[6]))$tarikh_kelulusan_temp = removeStringPadding($dataResitPerbendaharaanArr[6], " ", "R");

        //$tarikh_penyata_pemungut = convertDateFromTextfile($tarikh_penyata_pemungut_temp);
        $tarikh_kelulusan = convertDateFromTextfile($tarikh_kelulusan_temp);
        $tarikh_resit_perbendaharaan = convertDateFromTextfile($tarikh_resit_perbendaharaan_temp);

        $result = Api_CollectorStatement::from('collector_statement as cs')
            ->join('collector_statement_log as csl', 'csl.collector_statement_id', 'cs.id')
            ->whereDate('csl.date', '=', $tarikh_kelulusan)
            ->where([
                'cs.collector_statement_no'=> $no_penyata_pemungut,
                'cs.transaction_status_id'=> 4
            ])
            ->update([
                'cs.receipt_no' => $no_resit_perbendaharaan,
                'cs.receipt_date' => $tarikh_resit_perbendaharaan,
            ]);

        return $result;

    }

}
