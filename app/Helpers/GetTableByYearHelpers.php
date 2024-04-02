<?php

use App\Models\PermohonanCutiAttachmentDynamic;
use Illuminate\Support\Facades\Schema;
use App\Models\PermohonanCutiDynamic;
use App\Models\PermohonanCutiMaklumatAnakDynamic;
use App\Models\PermohonanCutiMaklumatKematianDynamic;
use App\Models\PermohonanGCRDynamic;
use App\Models\TetapanKelayakanCutiDynamic;

if (! function_exists('getTablePermohonanCutiDynamic')) {
    function getTablePermohonanCutiDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            PermohonanCutiDynamic::defineTableName($tahun);
            $permohonan_cuti_dynamic = new PermohonanCutiDynamic(); 
            $table_name = $permohonan_cuti_dynamic->getTable();

            if (!Schema::hasTable($table_name)) {
                createTablePermohonanCuti($table_name,$tahun);
            }
        }
        return $table_name;        
    }
}

if (! function_exists('getTablePermohonanCutiAttachmentDynamic')) {
    function getTablePermohonanCutiAttachmentDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            PermohonanCutiAttachmentDynamic::defineTableName($tahun);
            $permohonan_cuti_attachment_dynamic = new PermohonanCutiAttachmentDynamic(); 
            $table_name = $permohonan_cuti_attachment_dynamic->getTable();
    
            if (!Schema::hasTable($table_name)) {
                createTablePermohonanCutiAttachment($table_name,$tahun);
            }
        }
        return $table_name;
    }
}

if (! function_exists('getTablePermohonanCutiMaklumatAnakDynamic')) {
    function getTablePermohonanCutiMaklumatAnakDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            PermohonanCutiMaklumatAnakDynamic::defineTableName($tahun);
            $permohonan_cuti_maklumat_anak_dynamic = new PermohonanCutiMaklumatAnakDynamic(); 
            $table_name = $permohonan_cuti_maklumat_anak_dynamic->getTable();
    
            if (!Schema::hasTable($table_name)) {
                createTablePermohonanCutiMaklumatAnak($table_name,$tahun);
            }
        }
        return $table_name;
    }
}

if (! function_exists('getTablePermohonanCutiMaklumatKematianDynamic')) {
    function getTablePermohonanCutiMaklumatKematianDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            PermohonanCutiMaklumatKematianDynamic::defineTableName($tahun);
            $permohonan_cuti_maklumat_kematian_dynamic = new PermohonanCutiMaklumatKematianDynamic(); 
            $table_name = $permohonan_cuti_maklumat_kematian_dynamic->getTable();
    
            if (!Schema::hasTable($table_name)) {
                createTablePermohonanCutiMaklumatKematian($table_name,$tahun);
            }
        }
        return $table_name;
    }
}

if (! function_exists('getTableTetapanKelayakanCutiDynamic')) {
    function getTableTetapanKelayakanCutiDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            TetapanKelayakanCutiDynamic::defineTableName($tahun);
            $tetapan_kelayakan_cuti_dynamic = new TetapanKelayakanCutiDynamic(); 
            $table_name = $tetapan_kelayakan_cuti_dynamic->getTable();

            if (!Schema::hasTable($table_name)) {
                createTableTetapanKelayakanCuti($table_name,$tahun);
            }
        }
        return $table_name;

    }
}

if (! function_exists('getTablePermohonanGCRDynamic')) {
    function getTablePermohonanGCRDynamic($tahun){
        $table_name = "";
        $is_int =  ctype_digit(strval($tahun));
        if($tahun && $is_int==true){
            PermohonanGCRDynamic::defineTableName($tahun);
            $permohonan_gcr_dynamic = new PermohonanGCRDynamic(); 
            $table_name = $permohonan_gcr_dynamic->getTable();

            if (!Schema::hasTable($table_name)) {
                createTablePermohonanGCR($table_name,$tahun);
            }
        }
        return $table_name;        
    }
}