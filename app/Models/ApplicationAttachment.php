<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationAttachment extends Model
{
    use HasFactory;
    protected $table = 'application_attachment';
    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class, 'd_id');
    }
}
