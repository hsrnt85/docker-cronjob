<?php

    //----------------------------------------------------------------------------------------
    //STRING/ARRAY FORMATTING
    //----------------------------------------------------------------------------------------

    //ARRAY TO STRING LIST WITH SEPARATOR
    function ArrayToStringList($arrayData){
        $append = "";
        if($arrayData != ""){
            if(count($arrayData)>0){
                $append = "- ";
            }
            $separator = "<br/>- ";
            $stringData = implode($separator, $arrayData);

            return $append.$stringData;
        }

    }
    //ARRAY TO STRING WITH SEPARATOR
    function ArrayToString($arrayData){
        if($arrayData != ""){
            $separator = ",";
            $stringData = implode($separator, $arrayData);

            return $stringData;
        }
    }


    //ARRAY TO STRING LIST WITH . SEPARATOR
    function ArrayToDottedStringList($arrayData){
        $append = "";
        if($arrayData != ""){
            if(count($arrayData)>0){
                $append = " <li>  ";
            }
            $separator = "<br/> <li> ";
            $stringData = implode($separator, $arrayData);

            return $append.$stringData;
        }

    }

    //CHECK STRING ARRAY DATA INARRAY
    function inArray($arrayData, $value){

        if($arrayData != ""){
            $checkedArrayData = explode(',', $arrayData);
            return in_array($value, $checkedArrayData ) ? "1" : "";
        }

    }

    //MYSQL WHEREIN ARRAY
    function stringToArray($stringData, $separator){

        if($stringData != ""){

            $arrayData = array_map('intval', explode($separator, $stringData));
            return $arrayData;

        }
    }

    function removeComma($value){
        if(!empty($value)){
            $value = str_replace( ',', '', $value);
        }
        return $value;

    }

    function removeWhiteSpace($value){
        if(!empty($value)) {
            $value = trim($value);
        }

        return $value ?? null;
    }

    function upperText($text){
        if($text != ''){
            $text = strtoupper($text);
        }
        return $text ;
    }

    function capitalizeText($text){
        if($text != ''){
            $text = ucwords(strtolower($text));
        }
        return $text ;
    }

    function removeStringPadding($string, $pad_string, $pad_type)
    {
        if($string == null) $string = "";
        if($pad_type == "R"){  $string = rtrim($string,$pad_string); }
        elseif($pad_type == "L"){  $string = ltrim($string,$pad_string); }

        return $string;
    }

    //----------------------------------------------------------------------------------------
    //NUMBER FORMATTING
    //----------------------------------------------------------------------------------------

    //FORMAT NUMBER WITH COMMA
    function numberFormatComma($num){
        if(!empty($num)){

            if($num < 0){
                return('('. number_format((double)$num*(-1),2) .')');
            }else{
                return(number_format((double)$num,2));
            }

        }else{
            return $num="0.00";
        }
    }

    //FORMAT NUMBER WITHOUT COMMA
    function numberFormatNoComma($num){
        if(!empty($num)){

            return( number_format($num, 2, '.', '') );

        }else{
            return $num="0.00";
        }
    }

    //FORMAT NUMBER WITHOUT COMMA & DOT
    function numberFormatNoCommaNoDot($num){
        if(!empty($num)){

            return( number_format($num, 2, '', '') );

        }else{
            return $num="0.00";
        }
    }

    //FORMAT NUMBER TO WORDING
    function convertNumbertoStatementMal($number){
        if ( !is_string($number) && !is_float($number) && !is_int($number) )
        {
            return false;
        }

        if ( is_string($number) )
        {
            //we know it's a string.  see if there's a negative:
            if ( substr($number, 0, 1) == '-' )
            {
                $number = substr($number, 1);
                $number = $number * -1;
            }
        }
        $number = strval($number);

        if ( $number == '0' )
        {
            return "";
        }
        $negative = $number < 0 ? "" : ""; //"Negative" : "";

        $number = trim($number, '-');

        $split_by_decimal = explode(".", $number);
        $decimal_string = '';

        if ( count($split_by_decimal) > 1 )
        {
            if($split_by_decimal[1] != '00'){ $decimal_string =  " DAN SEN " . process_numberMal($split_by_decimal[1]);  }
        }

        return trim(preg_replace("#\s+#", " ", $negative . " " . process_numberMal($split_by_decimal[0]) .$decimal_string));
    }

    function process_numberMal($number, $depth = 0)
    {

        $group_designators = array(
            "",
            "RIBU",
            "JUTA",
            "BILLION",
            "TRILLION");

        $numbers = array(
            '1'=>"SATU",
            '2'=>"DUA",
            '3'=>"TIGA",
            '4'=>"EMPAT",
            '5'=>"LIMA",
            '6'=>"ENAM",
            '7'=>"TUJUH",
            '8'=>"LAPAN",
            '9'=>"SEMBILAN",
            '10'=>"SEPULUH",
            '11'=>"SEBELAS",
            '12'=>"DUA BELAS",
            '13'=>"TIGA BELAS",
            '14'=>"EMPAT BELAS",
            '15'=>"LIMA BELAS",
            '16'=>"ENAM BELAS",
            '17'=>"TUJUH BELAS",
            '18'=>"LAPAN BELAS",
            '19'=>"SEMBILAN BELAS",
            '20'=>"DUA PULUH",
            '30'=>"TIGA PULUH",
            '40'=>"EMPAT PULUH",
            '50'=>"LIMA PULUH",
            '60'=>"ENAM PULUH",
            '70'=>"TUJUH PULUH",
            '80'=>"LAPAN PULUH",
            '90'=>"SEMBILAN PULUH",
        );

        while ( strlen($number) > 0 )
        {
            if ( strlen($number) <= 3 )
            {
                $number_to_process = $number;
                $number = '';
            }
            else
            {
                $number_to_process = substr($number, strlen($number) - 3);
                $number = substr($number, 0, strlen($number) - 3);
            }

            if ( strlen($number_to_process) == 3 )
            {
                $tmp = $numbers[substr($number_to_process, 0, 1)];
                $output[] = $numbers[substr($number_to_process, 0, 1)];
                if(isset($tmp)){ $output[] = "RATUS"; }
                $number_to_process = substr($number_to_process, 1);
            }

            if ( isset($numbers[$number_to_process]) )
            {
                $output[] = $numbers[$number_to_process];
            }
            else
            {
                $tens = substr($number_to_process, 0, 1) . "0";
                $ones = substr($number_to_process, 1, 1);
                $output[] = $numbers[$tens];
                $output[] = $numbers[$ones];
            }

            //SET DESIGNATOR IF ONLY NUMBER EXIST
            if (preg_match('/^\S.*\S$/', implode(" ", $output))) {
                return process_numberMal($number, $depth+1) . " " .implode(" ", $output) . $group_designators[$depth]." SAHAJA";
            } else {
                return process_numberMal($number, $depth+1) ." " .implode(" ", $output)." SAHAJA";
            }

        }
    }


    //----------------------------------------------------------
    // CHECKING & RETURN INTERSECT ARRAY OF TWO ARRAY
    //----------------------------------------------------------
    function checkArrayKey($a,$b)
    {
        if ($a===$b) return 0;
        return ($a>$b)?1:-1;
    }

    function checkArrayValue($a,$b)
    {
        if ($a===$b) return 0;
        return ($a>$b)?1:-1;
    }

    function findIntersectTwoArray($arr1, $arr2){
        //return array_uintersect_uassoc($arr1, $arr2, "checkArrayKey","checkArrayValue");
        return array_uintersect($arr1, $arr2, 'compareDeepValue');
    }

    function compareDeepValue($val1, $val2)
    {
        return (strcmp($val1['ic_no'], $val2['ic_no']) && strcmp($val1['amount'], $val2['amount']));
    }
?>
