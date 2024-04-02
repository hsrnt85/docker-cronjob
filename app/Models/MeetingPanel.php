<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mail;
use Illuminate\Support\Facades\Storage;

class MeetingPanel extends Model
{
    use HasFactory;
    protected $table = 'meeting_panel';
    public $timestamps = false;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'm_id');
    }

}
