<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationQuartersCategory extends Model
{
    use HasFactory;
    protected $table = 'application_quarters_category';
    public $timestamps = false;

    public function quarters_category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_category_id');
    }

    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public static function updatePlacement($application, $quarters_id)
    {
        $update = self::where('data_status', 1)
                    ->where('application_id', $application->id)
                    ->where('is_selected', 1)
                    ->whereNull('quarters_id')
                    ->update([
                        'quarters_id' => $quarters_id,
                        'action_by' => loginId(),
                        'action_on' => currentDate(),
                    ]);
        
        return $update;
    }
}
