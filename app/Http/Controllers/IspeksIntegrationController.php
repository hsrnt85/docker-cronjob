<?php

namespace App\Http\Controllers;


use Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

use App\Models\Api\Api_CollectorStatement;
use App\Models\Api\Api_Journal;
use App\Models\Api\Api_IspeksIntegration;

class IspeksIntegrationController extends Controller
{
    public $time_stamp, $tahun, $bulan;
    public $kod_negeri, $kod_negeri_detail, $kod_agensi, $kod_jabatan, $kod_ptj;
    public $kod_swift_bank, $no_akaun_bank;

    public function transaction_list_in(){

        $collectorStatementList = Api_CollectorStatement::get_passed_collector_statement();
        $journalList = Api_Journal::get_passed_jurnal();

        return view(getFolderPath().'.transaction-list-in', compact('collectorStatementList','journalList'));
                 
    }

    public function transaction_list_out(){
        
        $resitPerbendaharaanList = [];

        $appEnv = config('env.app_env');
        if($appEnv == "local"){
            $directory = "";//DEV
            $files = Storage::disk('ispeks-doc-out')->files();//DEV
        }else{
            $directory = config('services.ispeks.folder_out');//PROD
            $files = Storage::disk('ftp-ispeks-doc')->files($directory);//PROD
        }

        //FILE LIST IN DIRECTORY
        if($files){
            foreach($files as $file) {

                $nama_fail_gpg = Str::replace($directory, '', $file);

                $resitPerbendaharaanList[] = array(
                    'nama_fail' => $nama_fail_gpg
                );
            }
        }

        $resitPerbendaharaanList = collect($resitPerbendaharaanList);
        //dd($resitPerbendaharaanList);
        return view(getFolderPath().'.transaction-list-out', compact('resitPerbendaharaanList'));
                 
    }

    public function ispeks_integration_list(){

        $ispeksIntegrationList = Api_IspeksIntegration::get_ispeks_integration_list();
        $file_path = base_path('resources/ispeks/in/txt/');

        return view(getFolderPath().'.ispeks-integration-list', compact('ispeksIntegrationList','file_path'));
                 
    }

    public function process_incoming(Request $request){

        //FILE -> Penyata Pemungut, Jurnal
        if($request->input('btnSubmit')!=null){

            $result = (new Api_IntegrasiIspeksIncomingController)->process_incoming();
            
            if($result){
                return redirect()->route('ispeksIntegrationIncoming.index')->with('success', 'Integrasi Ispeks berjaya! ');
            }else{
                return redirect()->route('ispeksIntegrationIncoming.index')->with('error', 'Integrasi Ispeks tidak berjaya!');
            }

        }else{
            return redirect()->route('ispeksIntegrationIncoming.index')->with('error', 'Integrasi Ispeks tidak berjaya!');
        }

    }

    public function process_outgoing(Request $request){
        
        //FILE -> Resit Perbendaharaan 
        if($request->input('btnSubmit')!=null){
           
            try{

                $result = (new Api_IntegrasiIspeksOutgoingController)->process_outgoing();
                
                if($result){
                    return redirect()->route('ispeksIntegrationOutgoing.index')->with('success', 'Integrasi Ispeks berjaya! ');
                }else{
                    return redirect()->route('ispeksIntegrationOutgoing.index')->with('error', 'Integrasi Ispeks tidak berjaya!');
                }
            
            }catch (\Exception $e) {
                return redirect()->route('ispeksIntegrationOutgoing.index')->with('error', 'Integrasi Ispeks tidak berjaya! '. $e);
            }
            
        }else{
            return redirect()->route('ispeksIntegrationOutgoing.index')->with('error', 'Integrasi Ispeks tidak berjaya!');
        }
      
    }

    public function download_fail_teks_ipeks($nama_fail){
        
        $path = base_path('resources/views/lampiran/fail-ispeks/'.$nama_fail);
        return response()->download($path);

    }


}
