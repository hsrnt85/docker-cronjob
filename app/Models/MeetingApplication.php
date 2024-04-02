<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingApplication extends Model
{
    use HasFactory;
    public $table = 'meeting_application';
    public $timestamps = false;
    
    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function application_status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id');
    }

    public function quarters_category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_category_id');
    }

}
