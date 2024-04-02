<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistPenalty extends Model
{
    use HasFactory;

    protected $table    = 'tenants_blacklist_penalty';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    protected $dates = [
        'penalty_date',
        'action_on',
        'delete_on',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id');
    }

    public function rate()
    {
        return $this->belongsTo(BlacklistPenaltyRateList::class, 'blacklist_penalty_rate_list_id');
    }

    public static function getAllBlacklistPenaltyByQuartersCategory($categoryId)
    {
        $data = self::with('tenant')
            ->where('data_status', 1)
            ->whereHas('tenant', function ($subQ) use ($categoryId) {
                $subQ->where('quarters_category_id', $categoryId);
            });

        return $data->get();
    }

    public static function getAllTenantsBlacklistPenalty()
    {
        $data = self::from('tenants_blacklist_penalty as tbp')->where('tbp.data_status', 1)
                ->join('tenants', function ($query) {
                    $query->on('tbp.tenants_id', '=', 'tenants.id')
                    ->whereNull('tenants.leave_status_id')->where('tenants.data_status',1)->addSelect('tenants.id', 'tenants.quarters_category_id' );
                })->get();

        return $data;
    }
}
