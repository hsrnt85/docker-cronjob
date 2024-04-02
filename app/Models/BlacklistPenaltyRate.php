<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistPenaltyRate extends Model
{
    use HasFactory;

    protected $table = 'blacklist_penalty_rate';
    public $timestamps = false;

    public function rates()
    {
        return $this->hasMany(BlacklistPenaltyRateList::class, 'blacklist_penalty_rate_id')->where('data_status', 1);
    }

    public static function getRateBasedOnMonthsApart($monthsApart, $penalty_date)
    {
        return self::with('rates')
            ->where('data_status', 1)
            ->where('effective_date', '<=', $penalty_date)
            ->orderBy('effective_date', 'desc')
            ->first() // get latest effective penalty rate
            ->rates
            ->filter(function ($item) use ($monthsApart) {
                return (
                    ($item['range_from'] <= $monthsApart && $item['range_to'] >= $monthsApart && $item['operator_id'] == 2) ||
                    ($item['range_from'] <= $monthsApart && $item['operator_id'] == 1)
                );
            })
            ->first();
    }
}
