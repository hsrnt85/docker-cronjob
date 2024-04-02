<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Support\Facades\File;

use App\Http\Controllers\Api_EmailNotificationController;
use App\Services\GPG;

use App\Models\Api\Api_BankAccount;
use App\Models\Api\Api_BankAccountPnj;
use App\Models\Api\Api_CollectorStatement;
use App\Models\Api\Api_CollectorStatementLog;
use App\Models\Api\Api_CollectorStatementVotList;
use App\Models\Api\Api_IspeksIntegration;
use App\Models\Api\Api_Journal;
use App\Models\Api\Api_JournalLog;
use App\Models\Api\Api_JournalVotList;
use App\Models\Api\Api_PaymentMethod;
use App\Models\Api\Api_FinanceDepartment;
use App\Models\Api\Api_FinanceState;

use Carbon\Carbon;

class Api_IntegrasiIspeksIncomingController extends Controller
{

    public $tahun, $bulan;
    public $kod_negeri, $kod_negeri_detail, $kod_agensi, $kod_jabatan, $nama_jabatan, $kod_ptj, $nama_ptj;
    public $kod_swift_bank, $no_akaun_bank;

    public function __construct(){

    }

    public function process_incoming(){

        $appEnv = config('env.app_env');

        self::get_data_kewangan();

        //------------------------------------------------------------------------------------------------------------------------
        // SECTION - PENYATA PEMUNGUT
        //------------------------------------------------------------------------------------------------------------------------
        $id_data_ispeks_integration_pp_arr = [];
        $jenis_bayaran_arr = [];
        $response_pp = 0;
        $bil_jenis_bayaran = 0;

        $senaraiJenisBayaran = Api_PaymentMethod::getPaymentMethod("");
   
        foreach($senaraiJenisBayaran AS $i => $jenisBayaran){

            $id_jenis_bayaran = $jenisBayaran->id;
            $kod_kaedah_bayaran = $jenisBayaran->ispeks_payment_code;
            $jenis_bayaran = $jenisBayaran->payment_method;

            $senaraiPenyataPemungut = Api_CollectorStatement::get_passed_collector_statement($id_jenis_bayaran);
           
            if($senaraiPenyataPemungut->count()>0){

                $id_data_ispeks_integration_pp_arr[$id_jenis_bayaran] = self::generate_fail_pp($appEnv, $senaraiPenyataPemungut, $jenisBayaran);

                //FOR CHECKING
                $jenis_bayaran_arr[] = array(
                    "id_jenis_bayaran" =>  $id_jenis_bayaran,
                    "kod_kaedah_bayaran" => $kod_kaedah_bayaran,
                    "jenis_bayaran" => $jenis_bayaran
                );

                $response_pp++;
                $bil_jenis_bayaran++;
            }

        }
       
        //SEND EMAIL NOTIFICATION SENARAI PENYATA PEMUNGUT
        if(!empty($id_data_ispeks_integration_pp_arr)){
            $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

            $dataIntegrasiIspeksPP = self::get_data_ispeks_integration_pp($jenis_bayaran_arr, $id_data_ispeks_integration_pp_arr);

            //SEND EMAIL NOTIFICATION FAIL TELAH DIHANTAR
            foreach($dataIntegrasiIspeksPP as $data){
                (new Api_EmailNotificationController())->fail_dihantar_ke_ispeks($data);
                sleep(3);
            }
    
            //LAPORAN PENYATA PEMUNGUT
            (new Api_EmailNotificationController())->senarai_penyata_pemungut($dataIntegrasiIspeksPP, $maklumat_agensi);

            //LAPORAN PENYATA PEMUNGUT TERPERINCI - LAMPIRAN 2(D)
            (new Api_EmailNotificationController())->senarai_penyata_pemungut_2d($dataIntegrasiIspeksPP, $maklumat_agensi);
        }
        //------------------------------------------------------------------------------------------------------------------------
        // END PENYATA PEMUNGUT SECTION
        //------------------------------------------------------------------------------------------------------------------------

        //------------------------------------------------------------------------------------------------------------------------
        // SECTION - JURNAL
        //------------------------------------------------------------------------------------------------------------------------
        $id_data_ispeks_integration_jurnal_arr = [];
        $response_jurnal = 0;

        $senaraiJurnal = Api_Journal::get_passed_jurnal();

        if($senaraiJurnal->count()>0){
            $id_data_ispeks_integration_jurnal_arr = self::generate_fail_jurnal($appEnv, $senaraiJurnal);
            $response_jurnal = $senaraiJurnal->count();
        }

        //SEND EMAIL NOTIFICATION SENARAI JURNAL
        if(!empty($id_data_ispeks_integration_jurnal_arr)){
            $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

            $dataIntegrasiIspeksJurnal = self::get_data_ispeks_integration_jurnal($id_data_ispeks_integration_jurnal_arr);

            //SEND EMAIL NOTIFICATION
            foreach($dataIntegrasiIspeksJurnal as $data){
                (new Api_EmailNotificationController())->fail_dihantar_ke_ispeks($data);
                sleep(3);
            }
        
            //LAPORAN JURNAL
            (new Api_EmailNotificationController())->senarai_jurnal($dataIntegrasiIspeksJurnal, $maklumat_agensi);
        }
        //------------------------------------------------------------------------------------------------------------------------
        // END JURNAL SECTION
        //------------------------------------------------------------------------------------------------------------------------

        return response()->json(['response_pp' => $response_pp, 'response_jurnal' => $response_jurnal]);

    }

    //----------------------------------------------------------------------------------------------------------
    //PROSES PENYATA PEMUNGUT
    //----------------------------------------------------------------------------------------------------------
    public function generate_fail_pp($appEnv, $senaraiPenyataPemungut, $jenisBayaran){

        //JENIS BAYARAN
        $id_jenis_bayaran = $jenisBayaran->id;
        $kod_kaedah_bayaran = $jenisBayaran->ispeks_payment_code;
        $jenis_bayaran = $jenisBayaran->payment_method;
        $kod_kaedah_bayaran = str_pad($kod_kaedah_bayaran, 1, " ", STR_PAD_RIGHT);

        $id_penyata_pemungut_arr = $senaraiPenyataPemungut->pluck('id')->toArray();

        $kategori_fail = "PPL";
        $nama_fail = $this->getNamaFail($kategori_fail, $id_jenis_bayaran);

        $bank_account_transit = Api_BankAccount::get_bank_account_by_type($id_jenis_bayaran , 2); // 2:Akaun Transit

        //SAVE DATA INTEGRASI
        $saveIntegrasiIspeks = $this->save_data_integrasi($nama_fail, $kategori_fail, $id_jenis_bayaran, 0);
        if($saveIntegrasiIspeks){

            $content = "";

            //START BATCH HEADER ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $jenis_rekod = '0';

            $kod_agensi = str_pad($this->kod_agensi, 8, " ", STR_PAD_RIGHT);

            //PENYATA PEMUNGUT
            $bil_penyata_pemungut = $senaraiPenyataPemungut->count();
            $bil_penyata_pemungut = str_pad($bil_penyata_pemungut, 3, "0", STR_PAD_LEFT);

            $jumlah_kutipan_penyata_pemungut = 0;
            $jumlah_kutipan_penyata_pemungut = $senaraiPenyataPemungut->sum('collection_amount');

            $jumlah_kutipan_penyata_pemungut = numberFormatNoCommaNoDot($jumlah_kutipan_penyata_pemungut);
            $jumlah_kutipan_penyata_pemungut = str_pad($jumlah_kutipan_penyata_pemungut, 15, "0", STR_PAD_LEFT);

            $content .= $jenis_rekod."|".$kod_agensi."|".$bil_penyata_pemungut."|".$jumlah_kutipan_penyata_pemungut."|"."\r\n";
            //END BATCH HEADER ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            $jumlah_amaun_vot = 0;
         
            foreach($senaraiPenyataPemungut as $i => $dataPenyataPemungut){
                //dd($dataPenyataPemungut);
                $senaraiLog = Api_CollectorStatementLog::get_log_penyata_pemungut($dataPenyataPemungut->id);
               
                //START DETAILS RECORD ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                $jenis_rekod = '1';

                $kod_jabatan = str_pad($this->kod_jabatan, 6, " ", STR_PAD_RIGHT);
                $kod_ptj = str_pad($this->kod_ptj, 8, " ", STR_PAD_RIGHT);
                $kod_negeri_detail = str_pad($this->kod_negeri_detail, 4, " ", STR_PAD_RIGHT);

                $no_penyata_pemungut = str_pad($dataPenyataPemungut->collector_statement_no, 15, " ", STR_PAD_RIGHT);

                $tarikh_penyata_pemungut = str_pad($dataPenyataPemungut->tarikh_penyata_pemungut, 8, " ", STR_PAD_RIGHT);
                $tarikh_posting_penyata_pemungut = currentDateSys('dmY');
                $tahun_kewangan = $dataPenyataPemungut->tahun_kewangan;

                $kod_panjar = "";
                $kod_panjar = str_pad($kod_panjar, 10, " ", STR_PAD_RIGHT);

                $no_slip_bank = str_pad($dataPenyataPemungut->bank_slip_no, 20, " ", STR_PAD_RIGHT);
                $tarikh_slip_bank = str_pad($dataPenyataPemungut->tarikh_slip_bank, 8, " ", STR_PAD_RIGHT);
                $tarikh_pungutan_dari = str_pad($dataPenyataPemungut->tarikh_pungutan_dari, 8, " ", STR_PAD_RIGHT);
                $tarikh_pungutan_hingga = str_pad($dataPenyataPemungut->tarikh_pungutan_hingga, 8, " ", STR_PAD_RIGHT);

                $status_slip_bank = "A";

                $kod_swift_bank = str_pad($this->kod_swift_bank, 8, " ", STR_PAD_RIGHT);
                $no_akaun_bank = str_pad($this->no_akaun_bank, 20, " ", STR_PAD_RIGHT);

                foreach($senaraiLog as $dataLog){

                    if($dataLog->transaction_status_id == 2){//PEGAWAI PENYEDIA

                        $nama_pegawai_penyedia = $dataLog->finance_officer_name;
                        $nama_pegawai_penyedia = str_pad($nama_pegawai_penyedia, 50, " ", STR_PAD_RIGHT);
                        $jawatan_pegawai_penyedia = $dataLog->position_name;
                        $jawatan_pegawai_penyedia = str_pad($jawatan_pegawai_penyedia, 40, " ", STR_PAD_RIGHT);
                        $tarikh_disediakan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);

                    }elseif($dataLog->transaction_status_id == 3){//PEGAWAI PENYEMAK

                        $nama_pegawai_penyemak = $dataLog->finance_officer_name;
                        $nama_pegawai_penyemak = str_pad($nama_pegawai_penyemak, 50, " ", STR_PAD_RIGHT);
                        $jawatan_pegawai_penyemak = $dataLog->position_name;
                        $jawatan_pegawai_penyemak = str_pad($jawatan_pegawai_penyemak, 40, " ", STR_PAD_RIGHT);
                        $tarikh_semakan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);

                    }elseif($dataLog->transaction_status_id == 4){//PEGAWAI PELULUS

                        $nama_pegawai_pelulus = $dataLog->finance_officer_name;
                        $nama_pegawai_pelulus = str_pad($nama_pegawai_pelulus, 50, " ", STR_PAD_RIGHT);
                        $jawatan_pegawai_pelulus = $dataLog->position_name;
                        $jawatan_pegawai_pelulus = str_pad($jawatan_pegawai_pelulus, 40, " ", STR_PAD_RIGHT);
                        $tarikh_kelulusan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);
                    }
                }


                $bil_chargeline = "000";
                $dk = "K";
                $kod_penjenisan_terimaan = str_pad("", 15, " ", STR_PAD_RIGHT);

                $program_aktiviti = "";
                $program_aktiviti = str_pad($program_aktiviti, 6, " ", STR_PAD_RIGHT);
                $projek = "";
                $projek = str_pad($projek, 10, " ", STR_PAD_RIGHT);
                $setia = "";
                $setia = str_pad($setia, 3, " ", STR_PAD_RIGHT);
                $sub_setia = "";
                $sub_setia = str_pad($sub_setia, 4, " ", STR_PAD_RIGHT);

                $cara_pembiayaan = str_pad("", 1, " ", STR_PAD_RIGHT);

                $recon_kod_akaun_alt = "";
                $recon_kod_akaun_alt = str_pad($recon_kod_akaun_alt, 10, " ", STR_PAD_RIGHT);

                $blank_42 = str_pad("", 50, " ", STR_PAD_RIGHT);
                $blank_43 = str_pad("", 10, " ", STR_PAD_RIGHT);
                //$blank_44 = str_pad("", 50, " ", STR_PAD_RIGHT);
                //$blank_45 = str_pad("", 8, " ", STR_PAD_RIGHT);
                //$blank_46 = str_pad("", 15, "0", STR_PAD_LEFT);
                $blank_47 = str_pad("", 100, " ", STR_PAD_RIGHT);
                $blank_48 = str_pad("", 10, " ", STR_PAD_RIGHT);
                $blank_49 = str_pad("", 50, " ", STR_PAD_RIGHT);
                $blank_50 = str_pad("", 8, " ", STR_PAD_RIGHT);
                $blank_51 = str_pad("", 15, " ", STR_PAD_RIGHT);
                $blank_52 = str_pad("", 20, " ", STR_PAD_RIGHT);

                $vot = "";
                $senaraiPenyataPemungutVot = Api_CollectorStatementVotList:: get_penyata_pemungut_vot($dataPenyataPemungut->id);

                $jumlah_amaun_vot = $senaraiPenyataPemungutVot->sum('amaun');
                $jumlah_amaun_vot = str_pad(numberFormatNoCommaNoDot($jumlah_amaun_vot), 15, "0", STR_PAD_LEFT);
                                
                $online_payment_refno = "";
                $online_payment_date = "";
                $online_payment_amount = 0;
                if(in_array($kod_kaedah_bayaran, ['U'])){
                    $online_payment_refno = $dataPenyataPemungut->online_payment_refno;
                    $online_payment_date = $tarikh_slip_bank;
                    $online_payment_amount = numberFormatNoCommaNoDot($jumlah_amaun_vot);
                }
                
                $online_payment_refno = str_pad($online_payment_refno, 50, " ", STR_PAD_RIGHT);
                $online_payment_date = str_pad($online_payment_date, 8, " ", STR_PAD_RIGHT);
                $online_payment_amount = str_pad($online_payment_amount, 15, "0", STR_PAD_LEFT);

                foreach($senaraiPenyataPemungutVot as $dataVot){

                    $vot = $dataVot->general_income_code;
                    $vot = str_pad($vot, 5, " ", STR_PAD_RIGHT);

                    $kod_akaun = $dataVot->ispeks_account_code;
                    $kod_akaun = str_pad($kod_akaun, 8, " ", STR_PAD_RIGHT);

                    $amaun_vot = numberFormatNoCommaNoDot($dataVot->amaun);
                    $amaun_vot = str_pad($amaun_vot, 15, "0", STR_PAD_LEFT);

                    $content .= $jenis_rekod."|".$kod_jabatan."|".$kod_ptj."|".$no_penyata_pemungut."|".$tarikh_penyata_pemungut."|".$tarikh_posting_penyata_pemungut."|".$tahun_kewangan."|".$kod_negeri_detail."|".$kod_ptj."|".$kod_panjar."|";
                    $content .= $no_slip_bank."|".$tarikh_slip_bank."|".$tarikh_pungutan_dari."|".$tarikh_pungutan_hingga."|".$status_slip_bank."|".$kod_swift_bank."|".$no_akaun_bank."|".$kod_kaedah_bayaran."|".$jumlah_amaun_vot."|";
                    $content .= $nama_pegawai_penyedia."|".$jawatan_pegawai_penyedia."|".$tarikh_disediakan."|".$nama_pegawai_penyemak."|".$jawatan_pegawai_penyemak."|".$tarikh_semakan."|".$nama_pegawai_pelulus."|".$jawatan_pegawai_pelulus."|".$tarikh_kelulusan."|";
                    $content .= $bil_chargeline ."|".$dk."|".$kod_penjenisan_terimaan."|".$vot."|".$kod_ptj."|".$program_aktiviti."|".$projek."|".$setia."|".$sub_setia."|".$cara_pembiayaan."|".$kod_akaun."|".$recon_kod_akaun_alt."|".$amaun_vot."|";
                    $content .= $blank_42."|".$blank_43."|".$online_payment_refno."|".$online_payment_date."|".$online_payment_amount."|".$blank_47."|".$blank_48."|".$blank_49."|".$blank_50."|".$blank_51."|".$blank_52."|";
                    $content .= "\r\n";

                }

            }

            //dd($content);
            //END DETAILS RECORD ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            //WRITE DATA TO FILE
            Storage::disk('ispeks-doc-in')->put($nama_fail, $content);

            //ENCRYPT FILE
            $fail_encrypted = (new GPG())->file_encryption_gnupg($nama_fail);

            //STORE FILE TO ISPEKS HUB
            if($appEnv == "local"){
                $ftp_file = Storage::disk('ispeks-doc-in')->put('gpg/'.$nama_fail.'.gpg', $fail_encrypted);//DEV
            }else{
                $ftp_file = Storage::disk('ftp-ispeks-doc')->put(config('services.ispeks.folder_in').$nama_fail.'.gpg', $fail_encrypted);//PROD
            }
            
            if($ftp_file){
                //File::delete(config('services.ispeks.local_folder_in').$nama_fail);//DEV

                //UPDATE DATA INTEGRASI
                $dataIntegrasiIspeks = $this->update_data_integrasi($nama_fail, 0);
                $id_data_ispeks_integration = $dataIntegrasiIspeks->id;

                //UPDATE PENYATA PEMUNGUT
                $this->update_penyata_pemungut($id_penyata_pemungut_arr, $id_data_ispeks_integration);

                return $id_data_ispeks_integration;

            }else{
                return null;
            }

        }else{
            return null;
        }


    }

    //----------------------------------------------------------------------------------------------------------
    //PROSES JURNAL
    //----------------------------------------------------------------------------------------------------------
    public function generate_fail_jurnal($appEnv, $senaraiJurnal){

        $id_data_ispeks_integration_jurnal_arr = [];

        $this->get_data_kewangan();

        $kategori_fail = "JRL";

        if($senaraiJurnal){
            //1 TEXT FILE = 1 JURNAL
            foreach($senaraiJurnal as $dataJurnal){

                $id_jurnal = $dataJurnal->id;
                $seconds = $dataJurnal->seconds;
                $nama_fail = "";
                $nama_fail = $this->getNamaFail($kategori_fail, $seconds);
                //$id_data_ispeks_integration_jurnal_arr[] = $nama_fail;
              
                //SAVE DATA INTEGRASI
                if(!empty($nama_fail)) $saveIntegrasiIspeks = $this->save_data_integrasi($nama_fail, $kategori_fail, 0, $id_jurnal);
                if($saveIntegrasiIspeks){

                    $content = "";

                    //START BATCH HEADER ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                    $jenis_rekod = '0';
                    $bil_jurnal = str_pad("1", 6, "0", STR_PAD_LEFT);
                    $no_jurnal = str_pad($dataJurnal->journal_no, 15, " ", STR_PAD_RIGHT);
                    $tarikh_jurnal = str_pad($dataJurnal->tarikh_jurnal, 8, " ", STR_PAD_RIGHT);
                    $jumlah_amaun_vot = numberFormatNoCommaNoDot($dataJurnal->amaun_debit);
                    $jumlah_amaun_vot = str_pad($jumlah_amaun_vot, 15, "0", STR_PAD_LEFT);
                    $kod_agensi = str_pad($this->kod_agensi, 8, " ", STR_PAD_RIGHT);
                    $jenis_jurnal = "1";

                    $content .= $jenis_rekod."|".$bil_jurnal."|".$this->tahun."|".$this->bulan."|".$no_jurnal."|".$tarikh_jurnal."|".$jumlah_amaun_vot."|".$kod_agensi."|".$jenis_jurnal."|"."\r\n";
                    //END BATCH HEADER ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                    $senaraiLog = Api_JournalLog::get_journal_log($id_jurnal);

                    //START DETAILS RECORD ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                    $jenis_rekod = '1';

                    $no_penyata_pemungut = str_pad($dataJurnal->collector_statement_no, 15, " ", STR_PAD_RIGHT);
                    $tarikh_penyata_pemungut = str_pad($dataJurnal->tarikh_penyata_pemungut, 8, " ", STR_PAD_RIGHT);;
                    $butiran = str_pad($dataJurnal->description, 50, " ", STR_PAD_RIGHT);

                    $cara_pembiayaan = "L";

                    $jabatan_bayar = $this->kod_jabatan;
                    $ptj_bayar = $this->kod_ptj;

                    $jabatan_sedia = $this->kod_jabatan;
                    $ptj_sedia = $this->kod_ptj;
                    $jabatan_dipertanggung = $this->kod_jabatan;
                    $ptj_dipertanggung = $this->kod_ptj;

                    foreach($senaraiLog as $dataLog){

                        if($dataLog->transaction_status_id == 2){//PEGAWAI PENYEDIA

                            $nama_pegawai_penyedia = $dataLog->finance_officer_name;
                            $nama_pegawai_penyedia = str_pad($nama_pegawai_penyedia, 50, " ", STR_PAD_RIGHT);
                            $jawatan_pegawai_penyedia = $dataLog->position_name;
                            $jawatan_pegawai_penyedia = str_pad($jawatan_pegawai_penyedia, 40, " ", STR_PAD_RIGHT);
                            $tarikh_disediakan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);

                        }elseif($dataLog->transaction_status_id == 3){//PEGAWAI PENYEMAK

                            $nama_pegawai_penyemak = $dataLog->finance_officer_name;
                            $nama_pegawai_penyemak = str_pad($nama_pegawai_penyemak, 50, " ", STR_PAD_RIGHT);
                            $jawatan_pegawai_penyemak = $dataLog->position_name;
                            $jawatan_pegawai_penyemak = str_pad($jawatan_pegawai_penyemak, 40, " ", STR_PAD_RIGHT);
                            $tarikh_semakan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);

                        }elseif($dataLog->transaction_status_id == 4){//PEGAWAI PELULUS

                            $nama_pegawai_pelulus = $dataLog->finance_officer_name;
                            $nama_pegawai_pelulus = str_pad($nama_pegawai_pelulus, 50, " ", STR_PAD_RIGHT);
                            $jawatan_pegawai_pelulus = $dataLog->position_name;
                            $jawatan_pegawai_pelulus = str_pad($jawatan_pegawai_pelulus, 40, " ", STR_PAD_RIGHT);
                            $tarikh_kelulusan = str_pad($dataLog->tarikh, 8, " ", STR_PAD_RIGHT);
                        }
                    }

                    $vot = "";
                    $senaraiJurnalVot = Api_JournalVotList::get_jurnal_vot($id_jurnal);
                    foreach($senaraiJurnalVot as $bil_jurnal_item => $dataVot){

                        $bil_jurnal_item++;
                        $bil_jurnal_item = str_pad($bil_jurnal_item, 3, " ", STR_PAD_RIGHT);

                        $vot = $dataVot->general_income_code;
                        $vot = str_pad($vot, 4, " ", STR_PAD_RIGHT);

                        $kod_akaun = $dataVot->ispeks_account_code;
                        $kod_akaun = str_pad($kod_akaun, 8, " ", STR_PAD_RIGHT);

                        $dk = ($dataVot->amaun_debit>0) ? "D" : "K";
                        $amaun_vot = ($dataVot->amaun_debit>0) ? $dataVot->amaun_debit : $dataVot->amaun_kredit;
                        $amaun_vot = numberFormatNoCommaNoDot($amaun_vot);
                        $amaun_vot = str_pad($amaun_vot, 15, "0", STR_PAD_LEFT);

                        $program_aktiviti = "";
                        $program_aktiviti = str_pad($program_aktiviti, 6, " ", STR_PAD_RIGHT);
                        $projek = "";
                        $projek = str_pad($projek, 10, " ", STR_PAD_RIGHT);
                        $setia = "";
                        $setia = str_pad($setia, 3, " ", STR_PAD_RIGHT);
                        $sub_setia = "";
                        $sub_setia = str_pad($sub_setia, 4, " ", STR_PAD_RIGHT);

                        $content .= $jenis_rekod."|".$bil_jurnal_item."|".$jabatan_sedia."|".$ptj_sedia."|".$jabatan_dipertanggung."|".$ptj_dipertanggung."|";
                        $content .= $vot."|".$program_aktiviti."|".$projek."|".$setia."|".$sub_setia."|".$cara_pembiayaan."|".$kod_akaun."|".$jabatan_bayar."|".$ptj_bayar."|".$dk."|".$amaun_vot."|".$butiran."|".$no_penyata_pemungut."|".$tarikh_penyata_pemungut."|";
                        $content .= $nama_pegawai_penyedia."|".$jawatan_pegawai_penyedia."|".$tarikh_disediakan."|".$nama_pegawai_penyemak."|".$jawatan_pegawai_penyemak."|".$tarikh_semakan."|".$nama_pegawai_pelulus."|".$jawatan_pegawai_pelulus."|".$tarikh_kelulusan."|";
                        $content .= "\r\n";
                    }


                    //END DETAILS RECORD ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                    //WRITE DATA TO FILE
                    Storage::disk('ispeks-doc-in')->put($nama_fail, $content);

                    //ENCRYPT FILE
                    $fail_encrypted = (new GPG())->file_encryption_gnupg($nama_fail);

                    //STORE FILE TO ISPEKS HUB
                    if($appEnv == "local"){
                        $ftp_file = Storage::disk('ispeks-doc-in')->put('gpg/'.$nama_fail.'.gpg', $fail_encrypted);//DEV
                    }else{
                        $ftp_file = Storage::disk('ftp-ispeks-doc')->put(config('services.ispeks.folder_in').$nama_fail.'.gpg', $fail_encrypted);//PROD
                    }

                    if($ftp_file){
                        //File::delete(config('services.ispeks.local_folder_in').$nama_fail);

                        //UPDATE DATA INTEGRASI
                        $dataIntegrasiIspeks = $this->update_data_integrasi($nama_fail, $id_jurnal);
                        $id_data_ispeks_integration = $dataIntegrasiIspeks->id;

                        //UPDATE PENYATA PEMUNGUT
                        $this->update_jurnal($id_jurnal, $id_data_ispeks_integration);

                        $id_data_ispeks_integration_jurnal_arr[] = $id_data_ispeks_integration;

                    }

                }//END IF $saveIntegrasiIspeks

            }//END IF foreach jurnal
            
            //dd($id_data_ispeks_integration_jurnal_arr);

            return $id_data_ispeks_integration_jurnal_arr;

        }else{
            return null;
        }
    }
    //----------------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------------------
    //GET INFO KEWANGAN
    public function get_data_kewangan()
    {
        $this->tahun = date('Y');
        $this->bulan = date('m');

        $dataJabatan = Api_FinanceDepartment::get_finance_department();
        $this->kod_jabatan = $dataJabatan->department_code;
        $this->kod_agensi = $dataJabatan->agency_code;
        $this->nama_jabatan = $dataJabatan->department_name;
        $this->kod_ptj = $dataJabatan->ptj_code;
        $this->nama_ptj = $dataJabatan->ptj_name;

        $dataJabatan = Api_FinanceState::get_finance_state();
        $this->kod_negeri = $dataJabatan->state_code;
        $this->kod_negeri_detail = $dataJabatan->state_code_details;

        //CORPORATE ID GIVEN BY PNJ
        $dataAkaunPNJ = Api_BankAccountPnj::get_bank_account();
        $this->kod_swift_bank = $dataAkaunPNJ->swift_code??'';
        $this->no_akaun_bank = $dataAkaunPNJ->account_no??'';

    }

    //----------------------------------------------------------------------------------------------------------
    //GET DATA LAPORAN - PENYATA PEMUNGUT
    //----------------------------------------------------------------------------------------------------------
    public function get_data_ispeks_integration_pp($jenis_bayaran_arr, $id_data_ispeks_integration_pp_arr){

        $dataIntegrasiIspeks = [];
        $nama_fail = "";

        foreach($jenis_bayaran_arr as $i => $dataJenisBayaran){

            $jenis_bayaran = $dataJenisBayaran['jenis_bayaran'];
            $id_jenis_bayaran = $dataJenisBayaran['id_jenis_bayaran'];

            $senarai_penyata_pemungut = [];
            $senaraiPenyataPemungut = Api_IspeksIntegration::get_ispeks_integration_pp($id_data_ispeks_integration_pp_arr[$id_jenis_bayaran], $id_jenis_bayaran);
      
            foreach($senaraiPenyataPemungut as $i => $dataPenyataPemungut){

                if($i==0){ $nama_fail = $dataPenyataPemungut->file_name; }

                $senaraiLog = Api_CollectorStatementLog::get_log_penyata_pemungut($dataPenyataPemungut->collector_statement_id);
    
                $no_penyata_pemungut = $dataPenyataPemungut->collector_statement_no;

                $tarikh_penyata_pemungut = $dataPenyataPemungut->tarikh_penyata_pemungut;
                $tarikh_pungutan_dari = $dataPenyataPemungut->tarikh_pungutan_dari;
                $tarikh_pungutan_hingga = $dataPenyataPemungut->tarikh_pungutan_hingga;
                $jumlah_kutipan= $dataPenyataPemungut->collection_amount; 
                $diproses_pada = $dataPenyataPemungut->action_on;

                $tarikh_disediakan = "";
                $tarikh_semakan = "";
                $tarikh_kelulusan = "";

                foreach($senaraiLog as $dataLog){
                    if($dataLog->transaction_status_id == 2){//PEGAWAI PENYEDIA
                        $tarikh_disediakan = $dataLog->tarikh_display;
                    }elseif($dataLog->transaction_status_id == 3){//PEGAWAI PENYEMAK
                        $tarikh_semakan = $dataLog->tarikh_display;
                    }elseif($dataLog->transaction_status_id == 4){//PEGAWAI PELULUS
                        $tarikh_kelulusan = $dataLog->tarikh_display;
                    }
                }

                $senarai_penyata_pemungut[] = array(
                    'kod_jabatan' => $this->kod_jabatan,
                    'nama_jabatan' => $this->nama_jabatan,
                    'kod_ptj' => $this->kod_ptj,
                    'nama_ptj' => $this->nama_ptj,
                    'no_penyata_pemungut' => $no_penyata_pemungut,
                    'amaun_penyata_pemungut' => $jumlah_kutipan,
                    'tarikh_penyata_pemungut' => $tarikh_penyata_pemungut,
                    'tarikh_pungutan_dari' => $tarikh_pungutan_dari,
                    'tarikh_pungutan_hingga' => $tarikh_pungutan_hingga,
                    'tarikh_sedia' => $tarikh_disediakan,
                    'tarikh_semak' => $tarikh_semakan,
                    'tarikh_lulus' => $tarikh_kelulusan
                );

            }

            //DATA LAMPIRAN 2(D)
            if($senarai_penyata_pemungut){

                $dataIntegrasiIspeks[] = array(
                    "nama_fail" => $nama_fail,
                    "jenis_bayaran" => $jenis_bayaran,
                    "bil_penyata_pemungut" => $senaraiPenyataPemungut->count(),
                    "jumlah_penyata_pemungut" => $senaraiPenyataPemungut->sum('collection_amount'),
                    "senarai_penyata_pemungut" => $senarai_penyata_pemungut,
                    "diproses_pada" => $diproses_pada
                );

            }

        }

        $dataIntegrasiIspeks = collect($dataIntegrasiIspeks);

        return $dataIntegrasiIspeks;
    }

    //----------------------------------------------------------------------------------------------------------
    //GET DATA LAPORAN - JURNAL
    //----------------------------------------------------------------------------------------------------------
    public function get_data_ispeks_integration_jurnal($id_data_ispeks_integration_jurnal_arr){

        $dataIntegrasiIspeks = [];
        $nama_fail = "";
        $senaraiJurnal = Api_IspeksIntegration::get_ispeks_integration_jurnal($id_data_ispeks_integration_jurnal_arr);

        foreach($senaraiJurnal as $i => $dataJurnal){

            $nama_fail = $dataJurnal->file_name;
            $no_jurnal = $dataJurnal->journal_no;
            $amaun_debit = $dataJurnal->amaun_debit;
            $amaun_kredit = $dataJurnal->amaun_kredit;
            $diproses_pada = $dataJurnal->action_on;

            $dataIntegrasiIspeks[] = array(
                "nama_fail" => $nama_fail,
                "no_jurnal" => $no_jurnal,
                "amaun_debit" => $amaun_debit,
                "amaun_kredit" => $amaun_kredit,
                "diproses_pada" => $diproses_pada,
            );
        }

        $dataIntegrasiIspeks = collect($dataIntegrasiIspeks);

        return $dataIntegrasiIspeks;
    }

    //----------------------------------------------------------------------------------------------------------
    //SAVE DATA INTEGRASI
    public static function save_data_integrasi($nama_fail, $kategori, $id_jenis_bayaran, $id_transaksi){

        $dataIntegrasiIspeks = new Api_IspeksIntegration;
        $dataIntegrasiIspeks->file_name = $nama_fail;
        $dataIntegrasiIspeks->category = $kategori;
        $dataIntegrasiIspeks->payment_method_id = $id_jenis_bayaran;
        $dataIntegrasiIspeks->data_status = 0;
        if($kategori == "JRL") $dataIntegrasiIspeks->journal_id = $id_transaksi;
        $dataIntegrasiIspeks->save();
        $dataIntegrasiIspeks->refresh();

        return $dataIntegrasiIspeks;

    }
    //UPDATE DATA INTEGRASI
    public function update_data_integrasi($nama_fail, $id_jurnal){

        $query = Api_IspeksIntegration::where(['file_name'=>$nama_fail, 'data_status' => 0]);
        if($id_jurnal>0) $query = $query->where('journal_id', $id_jurnal);
        $query = $query->update(['file_name_gpg' => $nama_fail.'.gpg', 'data_status' => 1, 'action_on' => currentDate()]);

        $dataIntegrasiIspeks = Api_IspeksIntegration::select('id','file_name','action_on')
                                        ->where('file_name',$nama_fail)->first();

        return $dataIntegrasiIspeks;

    }

    //----------------------------------------------------------------------------------------------------------
    //UPDATE DATA PENYATA PEMUNGUT
    public function update_penyata_pemungut($id_penyata_pemungut_arr, $id_data_ispeks_integration){
        //dd($id_data_ispeks_integration);
        $result = Api_CollectorStatement::whereIn('id',$id_penyata_pemungut_arr)
                ->update(['ispeks_integration_id' => $id_data_ispeks_integration]);

        return $result;

    }

    //----------------------------------------------------------------------------------------------------------
    //UPDATE DATA JURNAL
    public static function update_jurnal($id_jurnal, $id_data_ispeks_integration){

        $result = Api_Journal::where('id', $id_jurnal)
                ->update(['ispeks_integration_id' => $id_data_ispeks_integration]);

        return $result;

    }

    //
    public function getNamaFail($kategori_fail, $second){

        $curr_date_time1 = Carbon::now()->addSeconds($second);
        $date_time_pp1 = date('YmdHis', strtotime($curr_date_time1));
        $nama_fail_pp1 = $this->kod_negeri.'_'.$this->kod_agensi.'_'.$kategori_fail.'_'.$date_time_pp1;
        $nama_fail = $nama_fail_pp1;

        return $nama_fail;
    }

}
