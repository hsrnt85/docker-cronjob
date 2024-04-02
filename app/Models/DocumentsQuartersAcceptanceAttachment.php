<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsQuartersAcceptanceAttachment extends Model
{
    use HasFactory;
    protected $table = 'documents_quarters_acceptance_attachment';
    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(DocumentQuartersAcceptance::class, 'documents_quarters_acceptance_id');
    }

}
