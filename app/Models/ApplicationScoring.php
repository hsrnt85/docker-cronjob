<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationScoring extends Model
{
    use HasFactory;
    protected $table = 'application_scoring';
    public $timestamps = false;

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
