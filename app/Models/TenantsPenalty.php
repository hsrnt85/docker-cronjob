<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsPenalty extends Model
{
    use HasFactory;
    protected $table    = 'tenants_penalty';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    protected $dates = [
        'penalty_date',
    ];

    public static function getAllPenaltyByQuartersCategory($categoryId)
    {
        return self::select('tenants_penalty.*' )
                    ->join('tenants' , 'tenants.id', '=' ,'tenants_penalty.tenants_id')
                    ->where('tenants_penalty.data_status', 1)
                    ->where('tenants.quarters_category_id', $categoryId)
                    ->get();
    }

    public function tenants()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id');
    }


}
