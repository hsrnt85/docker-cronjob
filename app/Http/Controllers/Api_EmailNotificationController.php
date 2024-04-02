<?php

namespace App\Http\Controllers;

use App\Models\Api\Api_IspeksIntegration;
use App\Models\Api\Api_FinanceDepartment;

use Mail;
use App\Mail\Api_MailFailDitarikDiterima;
use App\Mail\Api_MailFailDitolak;
use App\Mail\Api_MailFailDihantar;
use App\Mail\Api_MailSenaraiPenyataPemungut;
use App\Mail\Api_MailSenaraiPenyataPemungut2d;
use App\Mail\Api_MailSenaraiJurnal;

class Api_EmailNotificationController extends Controller
{
    // public function test()
    // {

    //     $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

    //     $email_subject = 'iSPEKS - test email';
    //     $email_ispeks = config('services.ispeks.email');

    //     Mail::to($email_ispeks)->send(new Api_MailFailDihantar("", $maklumat_agensi, $email_subject, $email_ispeks));

    // }

    public function fail_ditarik_diterima_di_sistem_agensi($id_data_integrasi_ispeks)
    {

        $maklumat_fail = Api_IspeksIntegration::get_data_integrasi_ispeks($id_data_integrasi_ispeks);

        $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        $email_subject = 'iSPEKS - Integrasi Luar - Fail Diterima di Agensi';
        //$email_subject = 'iSPEKS - Integrasi Luar - Fail Diterima di Agensi (UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');

        Mail::to($email_ispeks)->send(new Api_MailFailDitarikDiterima($maklumat_fail, $maklumat_agensi, $email_subject, $email_ispeks));

    }

    public function fail_ditolak_di_sistem_agensi($id_data_integrasi_ispeks, $errorCounter, $errorContent)
    {

        $maklumat_fail = Api_IspeksIntegration::get_data_integrasi_ispeks($id_data_integrasi_ispeks);

        $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        $email_subject = 'iSPEKS - Integrasi Luar - Fail Tidak Diterima di Agensi';
        //$email_subject = 'iSPEKS - Integrasi Luar - Fail Tidak Diterima di Agensi (UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');

        Mail::to($email_ispeks)->send(new Api_MailFailDitolak($maklumat_fail, $maklumat_agensi, $email_subject, $email_ispeks, $errorCounter, $errorContent));

    }

    public function fail_dihantar_ke_ispeks($maklumat_fail)
    {
        $maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        $email_subject = 'iSPEKS - Integrasi Luar - Fail Dihantar ke iSPEKS';
        //$email_subject = 'iSPEKS - Integrasi Luar - Fail Dihantar ke iSPEKS (UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');
        $email_cc = config('services.ispeks.email_cc');
       // dd($email_ispeks." - ".$email_cc." - ".$maklumat_agensi);
        Mail::to($email_ispeks)->cc($email_cc)->send(new Api_MailFailDihantar($maklumat_fail, $maklumat_agensi, $email_subject, $email_ispeks));

    }

    public function senarai_penyata_pemungut($dataIntegrasiIspeks, $maklumat_agensi)
    {

        $nama_sistem = config('system.name');
        $nama_sistem_short = config('system.shortname');
        //$maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        //$email_subject = $nama_sistem.' - Laporan Penghantaran Fail Integrasi iSPEKS Pada '.currentDateSys('d-m-Y');
        $email_subject = 'RINGKASAN LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (PENYATA PEMUNGUT)';
        //$email_subject = $nama_sistem.' - RINGKASAN LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (PENYATA PEMUNGUT - UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');
        $email_cc = config('services.ispeks.email_cc');

        Mail::to($email_ispeks)->cc($email_cc)->send(new Api_MailSenaraiPenyataPemungut($nama_sistem, $nama_sistem_short, $maklumat_agensi, $email_subject, $email_ispeks, $dataIntegrasiIspeks));

    }

    public function senarai_penyata_pemungut_2d($dataIntegrasiIspeks, $maklumat_agensi)
    {

        $nama_sistem = config('system.name');
        $nama_sistem_short = config('system.shortname');
        //$maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        //$email_subject = 'LAPORAN PENGHANTARAN FAIL PENYATA PEMUNGUT INTEGRASI iSPEKS DENGAN '.$nama_sistem;
        $email_subject = 'LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (PENYATA PEMUNGUT)';
        //$email_subject = $nama_sistem.' - LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (PENYATA PEMUNGUT - UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');
        $email_cc = config('services.ispeks.email_cc');
        
        Mail::to($email_ispeks)->cc($email_cc)->send(new Api_MailSenaraiPenyataPemungut2d($nama_sistem, $nama_sistem_short, $maklumat_agensi, $email_subject, $email_ispeks, $dataIntegrasiIspeks));

    } 
    
    public function senarai_jurnal($dataIntegrasiIspeks, $maklumat_agensi)
    {

        $nama_sistem = config('system.name');
        $nama_sistem_short = config('system.shortname');
        //$maklumat_agensi = Api_FinanceDepartment::get_finance_department();

        //$email_subject = $nama_sistem.' - Laporan Penghantaran Fail Integrasi iSPEKS Pada '.currentDateSys('d-m-Y');
        $email_subject = 'RINGKASAN LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (JURNAL)';
        //$email_subject = $nama_sistem.' - RINGKASAN LAPORAN PENGHANTARAN FAIL INTEGRASI iSPEKS (JURNAL - UNTUK KEGUNAAN PENGUJIAN SAHAJA)';
        $email_ispeks = config('services.ispeks.email');
        $email_cc = config('services.ispeks.email_cc');
        
        Mail::to($email_ispeks)->cc($email_cc)->send(new Api_MailSenaraiJurnal($nama_sistem, $nama_sistem_short, $maklumat_agensi, $email_subject, $email_ispeks, $dataIntegrasiIspeks));

    }

}
