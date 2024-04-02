<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAppointmentAttachment extends Model
{
    use HasFactory;

    protected $table    = 'complaint_appointment_attachment';
    protected $primaryKey = 'id';
    protected $fillable = ['path_document'];

    public $timestamps  = false;
}