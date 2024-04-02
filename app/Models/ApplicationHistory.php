<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    use HasFactory;

    protected $table = 'application_history';
    public $timestamps = false;
    protected $dates = ['action_on'];

     public function status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id');
    }

    public function user_action_by()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
