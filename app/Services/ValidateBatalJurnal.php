<?php

namespace App\Services;

use Illuminate\Support\Str;

class ValidateBatalJurnal
{
    //-----------------------------------------------------------------------------------------------------------       
    // VALIDATE DATA COUNTER
    //-----------------------------------------------------------------------------------------------------------
    public static function validateDataCounter($line, $filteredLineArray, $totalLineColumn){
        if($filteredLineArray>0 || $filteredLineArray !=null){

            $countArray = count($filteredLineArray);
            if(!($countArray == $totalLineColumn))
            {
                //store message error
                $errorMsg[0]['data'] = $line;
                $errorMsg[0]['message']= self::errorValidateMsg('length_msg');
                $errorMsg[0]['actual_data_msg'] = 'Data yang diperlukan:'. $totalLineColumn.'. Data yang dihantar: '. $countArray;

            }
        }
    }

    
    //-----------------------------------------------------------------------------------------------------------   
    // VALIDATE LINE
    //-----------------------------------------------------------------------------------------------------------
    public function validateLine($line, $line_index, $last_line_index, $filteredLineArray)
    {
        if($filteredLineArray>0 || $filteredLineArray !=null){
            $validateLine = array();

            //Validate
            foreach ($filteredLineArray as $i => $data)
            {
                if($line_index == 0){
                    $validateLine[$line_index][] = Self::validateLine_0($line,$line_index, $i, $data);
                }
                else if($line_index == $last_line_index){
                    $validateLine[$line_index][] = Self::validateLastLine($line, $line_index, $i, $data);
                }
            }
        }
        return $validateLine;

    }

    public static function validateLine_0($line, $line_index, $i, $data)
    {
        $bil = $i + 1;
        $errorMsgContent = array();
        $length = Str::length($data);

        if($i == 0)
        {
            $lengthFormat = 1;

            if(!($length == $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 1)
        {
            $lengthFormat = 15; 

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 2)
        {
            $lengthFormat = 50;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 3)
        {
            $lengthFormat = 50;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 4)
        {
            $lengthFormat = 40;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 5)
        {
            $lengthFormat = 8; //dateFormat (ddmmyyyy)

            if(!($length == $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('date_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else
        {
            $errorMsgContent = [];

        }

        return  $errorMsgContent;

    }

    public static function validateLastLine($line, $line_index, $i, $data)
    {
        $errorMsgContent = array();
        $length = Str::length($data);
        $bil = $i + 1;

        if($i == 0)
        {
            $lengthFormat = 1;

            if(!($length == $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 1)
        {
            $lengthFormat = 2;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 2)
        {
            $lengthFormat = 250;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        
        return $errorMsgContent;

    }
    //-----------------------------------------------------------------------------------------------------------
    public static function errorValidateMsg($type)
    {
        if($type == 'length_msg')
        {
            $msg = 'Data yang dihantar melebihi data yang diperlukan :';
        }
        else if($type == 'date_msg')
        {
            $msg = 'Format tarikh yang dihantar berbeza dengan format yang telah ditetapkan.';
        }
        else
        {
            $msg = 'Format data yang dihantar salah.';
        }

        return $msg;

    }

}
