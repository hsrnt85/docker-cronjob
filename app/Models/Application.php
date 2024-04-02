<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    use HasFactory;

    protected $table = 'application';
    protected $dates = ['application_date_time', 'action_on', 'deleted_on'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_info()
    {
        return $this->belongsTo(UserInfo::class, 'users_info_id');
    }

    public function user_address_office()
    {
        return $this->belongsTo(UserAddressOffice::class, 'user_id');
    }
    // Cancel
    public function category()
    {
        return $this->belongsTo(QuartersCategory::class, 'quarters_category_id');
    }

    public function quarters_category()
    {
        return $this->belongsToMany(QuartersCategory::class, 'application_quarters_category', 'application_id', 'quarters_category_id')
            ->withPivot('is_selected', 'data_status', 'action_on', 'action_by');
    }
    public function quarters()
    {
        return $this->belongsTo(Quarters::class, 'quarters_id');
    }

    public function application_quarters_categories()
    {
        return $this->hasMany(ApplicationQuartersCategory::class, 'application_id');
    }

    public function selected_quarters()
    {
        return $this->application_quarters_categories->where('is_selected', 1)->first()->quarters;
    }

    public function selected_category()
    {
        return $this->application_quarters_categories->where('is_selected', 1)->first()->quarters_category;
    }

    public function scores()
    {
        return $this->hasMany(ApplicationScoring::class, 'application_id')->select('mark');
    }

    public function histories()
    {
        return $this->hasMany(ApplicationHistory::class, 'application_id');
    }
    public function current_status()
    {
        return $this->hasOne(ApplicationHistory::class, 'application_id')->latestOfMany();
    }
    // public function approved_by()
    // {
    //     // disemak oleh
    //     // where current_status = 3 lulus
    //     return $this->application_history->where('application_status_id', 3)->last()?->user_action_by?->name;
    // }
    // public function reviewed_by()
    // {
    //     // disemak oleh
    //     // where current_status = 2 semak
    //     return $this->application_history->where('application_status_id', 2)->last()?->user_action_by?->name;
    // }
    public function approved_by()
    {
        // disemak oleh
        // where current_status = 3 lulus
        return $this->application_review->where('application_status_id', 3)->last()?->officer?->user->name;
    }
    public function reviewed_by()
    {
        // disemak oleh
        // where current_status = 2 semak
        return $this->application_review->where('application_status_id', 2)->last()?->officer?->user->name;
    }
    public function application_status($application_status_id)
    {
        // status by id
        return $this->application_history->where('application_status_id', $application_status_id)->last()?->status?->status;
    }
    public function application_history()
    {
        return $this->hasMany(ApplicationHistory::class, 'application_id');
    }
    public function application_review()
    {
        return $this->hasMany(ApplicationReview::class, 'application_id');
    }
    public function applicant_salary()
    {
        return $this->hasOne(UserSalary::class, 'application_id', 'id');
    }
    public function quarters_offer_letter()
    {
        return $this->hasOne(QuartersOfferLetter::class, 'application_id', 'id');
    }

    public static function getDistinctQuartersCategoryFromApplication()
    {
        // get quarters category based on application
        // where status = 7
        $data = self::with('category')
            ->with('quarters_category')
            ->where('data_status', 1)
            ->whereHas('current_status', function ($q) {
                $q->where('application_status_id', 7);
            })
            ->whereHas('quarters_category', function ($q) {
                $q->where('is_selected', 1);
            })->get()
            ->pluck('quarters_category');

        return $data;
    }


    public static function getApplicationNeededPlacement($category)
    {
        // get application
        // param quarters category
        // where current_status = 7 lulus
        // where quarters = null (not yet set)

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 7); // 7:Lulus Mesyuarat
            })
            ->whereHas('quarters_category', function ($query) use ($category) {
                $query->where('quarters_category_id', $category->id);
                $query->where('is_selected', 1);
                $query->whereNull('quarters_id');
            })
            ->get();

        return $data;
    }

    public static function checkApplicationNeededPlacement($category, $application_id)
    {
        // check application exist
        // param quarters category
        // where current_status = 7 lulus
        // where quarters = null (not yet set)

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('quarters_category', function ($query) use ($category) {
                $query->where('quarters_category_id', $category->id);
                $query->where('is_selected', 1);
                $query->whereNull('quarters_id');
            })
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 7); // 7:Lulus Mesyuarat
            })
            ->where('id', $application_id)
            ->first();

        return $data;
    }


    public static function getApplicationEligibleForReplacement($category)
    {
        // get application
        // param quarters category
        // where current_status = 7 lulus
        // where quarters = null (not yet set)

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('quarters_category', function ($query) use ($category) {
                $query->where('quarters_category_id', $category->id);
                $query->where('is_selected', 1);
                $query->whereNotNull('quarters_id');
            })
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 7); // 7:Lulus Mesyuarat
            })
            ->get();

        return $data;
    }

    public static function getAcceptedOffer($district_id=null)
    {
        // current status = 11

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 11); // 11:Terima tawaran
              
            })
            ->orderBy('action_on', 'desc')
            ->get();

        return $data;
    }

    public static function getAcceptedOfferChecked($id)
    {
        // current status = 11

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 11); // 11:Terima tawaran
            })
            ->where('id', $id)
            ->first();

        return $data;
    }

    public static function getRejectedOffer()
    {
        // current status = 12

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 12); // 11:Tolak tawaran
            })
            ->orderBy('action_on', 'desc')
            ->get();

        return $data;
    }

    public static function getRejectedOfferChecked($id)
    {
        // current status = 12

        $data = self::where('data_status', 1)
            ->where('is_draft', 0)
            ->whereHas('current_status', function ($query) {
                $query->where('application_status_id', 12); // 12:Tolak tawaran
            })
            ->where('id', $id)
            ->first();

        return $data;
    }

    public static function getDistinctYear()
    {
        $data = self::select(DB::raw("YEAR(application_date_time) as tahun"))
            ->where('is_draft', 0)
            ->where('data_status', 1)
            ->groupBy('tahun')
            ->get();

        return $data;
    }

    public static function getApplicationMonthlyCount($tahun)
    {
        return self::select(
            DB::raw("MONTH(application_date_time) as month"),
            DB::raw("COUNT(id) as applications_count")
        )
            ->whereYear('application_date_time', $tahun)
            ->where('is_draft', 0)
            ->where('data_status', 1)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public static function laporanDinamikPemohon($from, $to, $district_id, $selectedStatus, $selectedServicesType, $ic)
    {
        // $statusMap = [
        //     1 => [12], // Tolak tawaran
        //     2 => [4, 6, 8, 9], // Tidak berjaya
        //     3 => [1, 2, 3, 5, 7, 11] // Aktif
        // ];

        $statusMap = [
            0 => [1], // Draf, Baru
            1 => [3, 5, 7, 11], // Berjaya
            2 => [4, 6, 8, 9], // Tidak berjaya
        ];
        //dd($selectedStatus);
        $reportData = Application::where('data_status', 1)
            ->whereHas('application_quarters_categories', function ($aqcSubq) use ($district_id) {
                $aqcSubq->whereHas('quarters_category', function ($qcSubq) use ($district_id) {
                    $qcSubq->where('district_id', $district_id);
                });
            });
        if ($selectedStatus!=5) {
            $reportData = $reportData->where('is_draft', 0);
        }else{
            $reportData = $reportData->where('is_draft', 1);
        }

        if (!$ic && ($from && $to)) {
            if ($selectedStatus!=5) {
                $reportData = $reportData->whereDate('application_date_time','>=', $from)->whereDate('application_date_time','<=', $to);
            }else{
                $reportData = $reportData->whereDate('application_draft_date_time','>=', $from)->whereDate('application_draft_date_time','<=', $to);
            }
        }
        if (isset($statusMap[$selectedStatus]) && $selectedStatus!=5) {
            $reportData = $reportData->whereHas('current_status', function ($currSubq) use ($statusMap, $selectedStatus) {
                $currSubq->whereIn('application_status_id', $statusMap[$selectedStatus]);
            });
        }

        if ($selectedServicesType || $ic) {
            $reportData = $reportData->whereHas('user', function ($userSubq) use ($selectedServicesType, $ic) {
                if($selectedServicesType) $userSubq->where('services_type_id', $selectedServicesType);
                if($ic) $userSubq->where('new_ic', $ic);
            });
        }
        
        return $reportData->with(['user', 'user.services_type', 'user.position', 'user.position_type', 'user.position_grade_code', 'user.position_grade', 'user.office.organization', 'user.marital_status', 'applicant_salary', 'user.spouse', 'user_info'])->get();
    }

    public static function laporanDinamikPemohonDitawarkan($from, $to, $district_id, $selectedQuartersCategory, $selectedOfferStatus, $selectedServicesType, $ic)
    {

        $statusMap = [
            3 => [11], // Terima Tawaran
            4 => [12], // Tolak Tawaran
        ];

        $reportData = Application::
        //from()->join('quarters_offer_letter as qol','application.id','=','qol.application_id')
        //    ->('')
            where('application.data_status', 1)
            ->where('application.is_draft', 0)
            ->whereHas('application_quarters_categories', function ($aqcSubq) use ($district_id, $selectedQuartersCategory) {
                $aqcSubq = $aqcSubq->where('is_selected', 1);
                if ($selectedQuartersCategory) {
                    $aqcSubq = $aqcSubq->where('quarters_category_id', $selectedQuartersCategory);
                }
                $aqcSubq->whereHas('quarters_category', function ($qcSubq) use ($district_id) {
                    $qcSubq->where('district_id', $district_id);
                });
            });
        if (!$ic && ($from && $to)) {
            $reportData = $reportData->whereDate('application_date_time','>=', $from)->whereDate('application_date_time','<=', $to);
        }
        if (isset($statusMap[$selectedOfferStatus])) {
            $reportData = $reportData->whereHas('current_status', function ($currSubq) use ($statusMap, $selectedOfferStatus) {
                $currSubq->whereIn('application_status_id', $statusMap[$selectedOfferStatus]);
            });
        }else{
            $reportData = $reportData->whereHas('current_status', function ($currSubq)  {
                $currSubq->whereIn('application_status_id', [11,12]);
            });
        }

        if ($selectedServicesType || $ic) {
            $reportData = $reportData->whereHas('user', function ($userSubq) use ($selectedServicesType, $ic) {
                if($selectedServicesType) $userSubq->where('services_type_id', $selectedServicesType);
                if($ic) $userSubq->where('new_ic', $ic);
            });
        }
        
        return $reportData->with(['quarters_offer_letter','user', 'user.services_type', 'user.position', 'user.position_type', 'user.position_grade_code', 'user.position_grade', 'user.office.organization', 'user_info'])->get();
    }
}
