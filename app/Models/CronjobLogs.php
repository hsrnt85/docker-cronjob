<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronjobLogs extends Model
{
    use HasFactory;
    protected $table = 'cronjob_logs';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function cronjobType()
    {
        return $this->belongsTo(CronjobType::class, 'cronjob_type_id');
    }

}
