<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuartersOfferLetter extends Model
{
    use HasFactory;

    protected $table        = 'quarters_offer_letter';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    protected $dates        = ['letter_date', 'final_confirmation_date', 'action_on', 'delete_on'];

    // private static $ref_no = 'SUKJ.BKP.200-9/3/';

    public static function newOfferLetter($application, $rental, $letter_ref_no)
    {
        $offerLetter = new self();
        $offerLetter->application_id    = $application->id;
        $offerLetter->letter_ref_no     = $letter_ref_no;
        // $offerLetter->running_no        = "";
        $offerLetter->letter_date       = currentDate();
        $offerLetter->rental_rate       = $rental;
        $offerLetter->final_confirmation_date = Carbon::now()->addDays(14)->format('Y-m-d');
        // $offerLetter->manager           = 'NISA';
        $offerLetter->data_status       = 1;
        $offerLetter->action_by         = loginId();
        $offerLetter->action_on         = currentDate();
        $offerLetter->save();
    }
}
