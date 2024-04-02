<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalType extends Model
{
    use HasFactory;
    protected $table = 'journal_type';
    public $timestamps = false;

    public static function get_journal_type(){

        $data = self::select('id','journal_type')->where('data_status' , 1)->get();

        return $data;
    }

}
