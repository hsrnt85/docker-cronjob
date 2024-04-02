<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationPanel extends Model
{
    use HasFactory;

    protected $table = 'invitation_panel';
    protected $primaryKey = 'id';
    public $timestamps = false;

}
