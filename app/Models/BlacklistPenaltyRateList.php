<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistPenaltyRateList extends Model
{
    use HasFactory;
    protected $table = 'blacklist_penalty_rate_list';
    public $timestamps = false;


    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function blacklist_penalty_rate()
    {
        return $this->belongsTo(BlacklistPenaltyRate::class, 'blacklist_penalty_rate_id');
    }

    public static function getLatestRates()
    {
        $data = self::where('data_status', 1)
        ->whereHas('blacklist_penalty_rate', function ($subQ) {
            $subQ->where('data_status', 1)
            ->orderBy('effective_date', 'desc')
            ->limit(1);
        });
        return $data->toSql();
    }
}
