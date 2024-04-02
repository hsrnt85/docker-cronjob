<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    use HasFactory;

    protected $table = 'approval_status';

    public static function getAllStatus()
    {
        $data = self::where([
            'data_status' => 1
        ])
        ->get();

        return $data;
    }
}
