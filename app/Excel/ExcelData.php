<?php

namespace App\Excel;

use Maatwebsite\Excel\Concerns\ToArray;

class ExcelData implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}