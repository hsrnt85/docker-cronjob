<?php

$msgArr = [];
$lbl_payment_notice_date_required = "Sila Masukkan Tarikh Notis Bayaran ";

$i=0;
for($month=1;$month<=12;$month++){
    $month_name = getMonthName($month);
    $msgArr['payment_notice_date_'.$month.'.required'] = $lbl_payment_notice_date_required.$month_name;
}

return $msgArr;
