<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    use HasFactory;

    protected $table    = 'quarters_offer_letter';
    public $timestamps  = false;
    protected $dates    = ['letter_date', 'final_confirmation', 'action_on', 'deleted_on'];

}
