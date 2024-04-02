<?php

namespace App\Services;

use Illuminate\Support\Str;

class ValidateResitPerbendaharaan
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
    public function validateLine($line, $line_index, $filteredLineArray)
    {
        if($filteredLineArray>0 || $filteredLineArray !=null){
            $validateLine = array();

            //Validate
            foreach ($filteredLineArray as  $i => $data)
            {
                if($line_index == 0){
                    $validateLine[$line_index][] = Self::validateLine_0($line,$line_index, $i, $data);
                }
                else if($line_index == 1){
                    $validateLine[$line_index][] = Self::validateLine_1($line, $line_index, $i, $data);
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
            $lengthFormat = 8; //dd($length);

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
            $lengthFormat = 3;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
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

    public static function validateLine_1($line, $line_index, $i, $data)
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
            $lengthFormat = 6;

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
            $lengthFormat = 8;

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
            $lengthFormat = 15;

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
            $lengthFormat = 8; //dateFormat (ddmmyyyy)

            if(!($length == $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('date_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 5)
        {
            $lengthFormat = 13;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 6)
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
        else if($i == 7)
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
        else if($i == 8)
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
        else if($i == 9)
        {
            $lengthFormat = 20;

            if(!($length <= $lengthFormat))
            {
                $errorMsgContent[$i]['lengthFormat'] = self::errorValidateMsg('format_msg');
                $errorMsgContent[$i]['bil'] = $bil;
                $errorMsgContent[$i]['lengthData'] = $line;
                $errorMsgContent[$i]['line_index'] = $line_index;
            }
        }
        else if($i == 10)
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
