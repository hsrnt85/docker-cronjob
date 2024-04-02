<?php

    use Carbon\Carbon;

    function getMonthName($month){

        if($month==1) $month_name = "Januari";
        else if($month==2) $month_name = "Februari";
        else if($month==3) $month_name = "Mac";
        else if($month==4) $month_name = "April";
        else if($month==5) $month_name = "Mei";
        else if($month==6) $month_name = "Jun";
        else if($month==7) $month_name = "Julai";
        else if($month==8) $month_name = "Ogos";
        else if($month==9) $month_name = "September";
        else if($month==10) $month_name = "Oktober";
        else if($month==11) $month_name = "November";
        else if($month==12) $month_name = "Disember";
        else $month_name = "";
        return $month_name;

    }

    function currentDateTimeZone(){
    $format = 'Y-m-d H:i';
    $date = date($format).' GMT+8:00';
    return $date;
    }

    function currentDate(){
        $format = 'Y-m-d H:i:s';
        return date($format);
    }

    function currentDateDb(){
        $format = 'Y-m-d';
        return date($format);
    }

    function currentDateSys($format=""){
        if($format=='') $format = 'd/m/Y';
        return date($format);
    }

    function currentDateTimeFilename(){
        $format = 'YmdHis';
        return date($format);
    }

    function currentMonth(){
        $format = 'm';
        return date($format);
    }
    
    function currentMonthInYear($year){
        $format = 'm';
        if($year <= currentYear()) return "12";
        else return date($format);
    }

    function currentYear(){
        $format = 'Y';
        return date($format);
    }

    function currentYearTwoDigit(){
        $format = 'y';
        return date($format);
    }

    //CONVERT DATE dmY
    function convertDateFromTextfile($date=''){
        //$date sample : '08042023'
        $convertDate = "";
        if($date!=''){
            $date = str_split($date, 2);

            $day    = $date[0];
            $month  = $date[1];
            $year   = $date[2].$date[3];

            $convertDate = $year.'-'.$month.'-'.$day;
        }

        return $convertDate;
    }

    function getDateFromDateTime($date_time){
        $date="";
        if($date_time!=''){
            $date = Carbon::parse($date_time)->locale('ms-MY')->translatedFormat('Y-m-d');
        }
        return date($date);
    }

    function getYearFromDate($date){
        $year="";
        if($date!=''){
            $year = Carbon::parse($date)->locale('ms-MY')->translatedFormat('Y');
        }
        return date($year);
    }

    function getMonthFromDate($date){
        $month="";
        if($date!=''){
            $month = Carbon::parse($date)->locale('ms-MY')->translatedFormat('m');
        }
        return date($month);
    }

    function getStartDateOfMonth($date){
        $final_date="";
        if($date!=''){
            $final_date = Carbon::parse($date)->startOfMonth()->toDateString();
        }
        return $final_date;
    }

    function getEndDateOfMonth($date){
        $final_date="";
        if($date!=''){
            $final_date = Carbon::parse($date)->endOfMonth()->toDateString();
        }
        return $final_date;
    }

    function getEndDayOfMonth($date){
        $final_date="";
        if($date!=''){
            $final_date = Carbon::parse($date)->endOfMonth()->locale('ms-MY')->translatedFormat('d');
        }
        return $final_date;
    }

    function getPrevMonth($date, $month){
        $month_prev = "";
        if($date!=''){
            $month_prev = Carbon::parse($date)->subMonths($month)->locale('ms-MY')->translatedFormat('m');
        }
        return $month_prev;
    }
    function getPrevDay($date, $day){
        $month_prev = "";
        if($date!=''){
            $month_prev = Carbon::parse($date)->subDay($day)->locale('ms-MY')->translatedFormat('Y-m-d');
        }
        return $month_prev;
    }
    //CONVERT DATE TO d/m/Y
    function convertDateSys($date=''){
        //$date sample : '2022-04-08'
        //result : 08/04/2022
        if($date!='') $date = date('d/m/Y', strtotime($date));
        return $date;
    }
    //CONVERT DATE TO d/m/Y
    function convertDateTimeSys($date=''){
        //$date sample : '2022-04-08'
        //result : 08/04/2022
        if($date!='') $date = date('d/m/Y h:iA', strtotime($date));
        return $date;
    }
    //CONVERT DATE TO Y-m-d
    function convertDateDb($date=''){
        //$date sample : '04-08-2022'-> d/m/Y
        //result : 2022-04-08
        if($date!='') $date = date('Y-m-d', strtotime($date));
        return $date;
    }

     function convert_date_db($date){ // PENYATA PEMUNGUT
        if(!empty($date)){
            $y = substr($date,6,4);
            $m = substr($date,3,2);
            $d = substr($date,0,2);
            $date = $y."-".$m."-".$d;
        }
        return $date;

    }

    //CONVERT CALENDAR DATE TO Y-m-d
    function convertDatepickerDb($date=''){
        //$date sample : '08/04/2022' -> m/d/Y
        if($date!=''){
            $date = Carbon::createFromFormat('d/m/Y', $date);
            $date = date('Y-m-d', strtotime($date));  // dd($date);
        }
        return $date;
    }

    //CONVERT DATE TO d F Y
    function convertDateLetter($date=''){
        //$date sample : '2022-04-08'
        //result : 08 April 2022
        Carbon::setlocale(config('app.locale'));
        if($date!=''){
            $date = Carbon::parse($date)->locale('ms-MY')->translatedFormat('d F Y');
        }
        return $date;
    }

    //CONVERT DATE TO F Y
    function convertDateToMonthYear($date=''){
        //$date sample : '2022-04-08'
        //result : April 2022
        Carbon::setlocale(config('app.locale'));
        if($date!=''){
            $date = Carbon::parse($date)->locale('ms-MY')->translatedFormat('F Y');
        }
        return $date;
    }


    //CONVERT TIME AM, PM
    function convertTime($time=''){
        //$time sample : '22:15:00'
        if($time!='') $time = date('h:i A', strtotime($time));
        return $time;
    }

    //GWT DATE DIFF BY MONTH
    function getDateDiffByMonth($dateFrom, $dateTo)
    {
        $dateFrom = new Carbon($dateFrom);
        $dateTo = new Carbon($dateTo);

        $dateFrom->setDay(1);

        return $dateFrom->diffInMonths($dateTo) + 1;
    }
?>
