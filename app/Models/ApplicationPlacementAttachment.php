<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPlacementAttachment extends Model
{
    use HasFactory;
    protected $table = 'application_placement_attachment';
    public $timestamps = false;

    public static function saveAttachment($application_id, $file)
    {
        //dd($file);
        $path = $file->store('documents/applications_placement', 'assets-upload');

        $attachment = new self;
        $attachment->application_id  = $application_id;
        // $attachment->letter_ref_no   = $letter_ref_no;
        $attachment->path_document   = $path;
        $attachment->action_by       = loginId();
        $attachment->action_on       = currentDate();

        $attachment->save();

        return $attachment;
    }

}
