<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuartersCategory extends Model
{
    use HasFactory;

    protected $table    = 'quarters_category';
    public $timestamps  = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id')->where('data_status', 1);
    }

    public function landed_type()
    {
        return $this->belongsTo(LandedType::class, 'landed_type_id')->where('data_status', 1);
    }

    public function category()
    {
        return $this->belongsTo(QuartersCategory::class, 'id');
    }

    public function quartersClass()
    {
        return $this->belongsToMany(QuartersClass::class, 'quarters_cat_class', 'q_cat_id', 'q_class_id')->withPivot('id', 'data_status', 'action_by', 'action_on', 'delete_by', 'delete_on');
    }

    public function class()
    {
        return $this->hasMany(QuartersCategoryClass::class, 'q_cat_id');
    }

    public function quarters()
    {
        return $this->hasMany(Quarters::class, 'quarters_cat_id')->where('data_status', '=', 1);
    }

    public function application()
    {
        return $this->belongsToMany(Application::class, 'application_quarters_category', 'quarters_category_id', 'application_id');
    }

    public function latest_tenant()
    {
        return $this->hasOne(Tenant::class, 'quarters_id')->latestOfMany();
    }

    public function allTenants()
    {
        return $this->hasMany(Tenant::class, 'quarters_category_id')->where('data_status', 1);
    }

    // public function tenants()
    // {
    //     return $this->hasMany(Tenant::class, 'quarters_category_id')->where('data_status', '=', 1);
    // }

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'quarters_category_id')->where('data_status', 1)
            ->select('id')
            ->where(function ($query) {
                $query->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            });
    }

    public function tenants_johorpay()
    {
        return $this->hasMany(Tenant::class, 'quarters_category_id')->where('data_status', 1)
            ->select('id')
            ->where(function ($query) {
                $query->whereIn('services_type_id', [1, 2, 5]) //JOHORPAY
                    ->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            });
    }
    public function tenants_by_acceptance_date($year, $month)
    {
        return $this->hasMany(Tenant::class, 'quarters_category_id')->where('data_status', 1)
            ->select('id')
            ->where(function ($query) use($year, $month){
                $query->whereYear('quarters_acceptance_date', $year)->whereMonth('quarters_acceptance_date', $month)->whereNull('leave_status_id')
                    ->orWhere('leave_status_id', '=', 0);     // belum keluar
            });
    }

    public function quarters_category_application()
    {
        return $this->hasMany(ApplicationQuartersCategory::class, 'quarters_category_id');
    }

    public function getAllClassIdArr()
    {
        return $this->quartersClass->pluck('id');
    }

    public function complaint()
    {
        return $this->hasMany(Complaint::class, 'quarters_id');
    }

    public function routine_inspection()
    {
        return $this->hasMany(RoutineInspection::class, 'quarters_category_id');
    }

    //Share With Dynamic Reporting
    public static function getAllQuartersCategory($district_id = null, $landedType = null, $categoryId = null)
    {
        $data = self::with('district')
            ->with('landed_type')
            ->where('data_status', 1);

        if ($district_id) $data = $data->where('district_id', $district_id);
        
        if ($landedType) {
            $data = $data->where('landed_type_id', $landedType);
        }

        if($categoryId) {
            $data = $data->where('id', $categoryId);
        }
        
        $data = $data->orderBy('name')->get();

        return $data;
    }

    public static function getDistinctQuartersCategoryForPlacement($district_id)
    {
        // get quarters category based on application
        // where application category is_selected = 1
        // where quarters null
        // where application status = 7
        // filter by officer's district

        $data = self::with('application')
            ->where('data_status', 1)
            ->whereHas('quarters_category_application', function ($q) use ($district_id) {

                if ($district_id) {
                    $q->where('district_id', $district_id);
                }
                $q->where('is_selected', 1)
                    ->whereNull('quarters_id')

                    ->whereHas('application.current_status', function ($q2) {
                        $q2->where('application_status_id', 7);
                    });
            })
            ->get()
            ->unique();

        return $data;
    }

    public static function getDistinctQuartersCategoryForReplacement()
    {
        // get quarters category based on application
        // where application status = 7
        // where application category is_selected = 1
        // where quarters not null

        $data = self::with('application')
            ->where('data_status', 1)
            ->whereHas('application', function ($q) {
                $q->whereIs_selected(1);
                //$q->wherenotNull('quarters_id');
                $q->whereHas('current_status', function ($q2) {
                    $q2->where('application_status_id', 7);
                });
            })
            ->get()
            ->unique();

        return $data;
    }


    public static function getAvailableQuartersCategory()
    {
        // get free quarters category
        // unit_no not null
        // not assigned

        $data = self::where('data_status', 1)
            ->whereHas('quarters', function ($q) {
                $q->whereNotNull('unit_no')
                    ->where('data_status', 1)
                    ->whereDoesntHave('application_quarters_categories');
            })
            ->get()
            ->unique();

        return $data;
    }

    public static function getDistinctQuartersCategoryForTenant($district_id = null)
    {
        $data = self::where('data_status', 1)
            ->whereHas('allTenants', function ($q) {
                $q->where('data_status', 1);
            })
            ->distinct();

        if ($district_id) {
            $data = $data->where('district_id', $district_id);
        }

        $list = $data->get();

        return $list;
    }

    public static function getComplaintStatistic($from, $to, $categoryId = null, $districtId = null)
    {
        $data = self::with([
            'quarters' => function ($quarters_query) use ($from, $to) {
                $quarters_query->withCount([
                    'complaints as total_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2);
                    },
                    'complaints as new_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2)
                            ->where('complaint_status_id', 0);
                    },
                    'complaints as active_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2)
                            ->where('complaint_status_id', 1);
                    },
                    'complaints as rejected_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2)
                            ->where('complaint_status_id', 2);
                    },
                    'complaints as done_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2)
                            ->where('complaint_status_id', 3);
                    },
                    'complaints as recurring_complaints' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 2)
                            ->where('complaint_status_id', 4);
                    }
                ]);
            }
        ])
            ->where('data_status', 1);

        if ($districtId) $data = $data->where('district_id', $districtId);
        if ($categoryId) $data = $data->where('id', $categoryId);

        $data = $data->orderBy('name')->get();

        return $data;
    }

    public static function getDamageStatistic($from, $to, $categoryId = null, $districtId = null)
    {
        $data = self::with([
            'quarters' => function ($quarters_query) use ($from, $to) {
                $quarters_query->withCount([
                    'complaints as jumlah' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 1);
                    },
                    'complaints as dalam_pemantauan' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 1)
                            ->whereIn('complaint_status_id', [0, 1]);
                    },
                    'complaints as dalam_penyelenggaraan' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 1)
                            ->where('complaint_status_id', 5);
                    },
                    'complaints as ditolak' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 1)
                            ->where('complaint_status_id', 2);
                    },
                    'complaints as selesai' => function ($complaints_query) use ($from, $to) {
                        $complaints_query->where('data_status', 1)
                            ->whereBetween('complaint_date', [$from, $to])
                            ->where('complaint_type', 1)
                            ->where('complaint_status_id', 3);
                    }
                ]);
            }
        ])
            ->where('data_status', 1);

        if ($districtId) $data = $data->where('district_id', $districtId);
        if ($categoryId) $data = $data->where('id', $categoryId);

        $data = $data->orderBy('name')->get();

        return $data;
    }

    public static function getQuartersCategoryStatistic()
    {
        $data = self::withCount([
            'quarters as total_quarters' => function ($quartersQ) {
                $quartersQ->where('data_status', 1);
            },
            'quarters as total_has_tenant' => function ($quartersQ) {
                // $quartersQ->whereHas('latest_tenant', function($tenantQ){
                //     $tenantQ->where('leave_status_id', null);
                // });
                $quartersQ->whereHas('current_active_tenant');
            },
            'quarters as total_rosak' => function ($quartersQ) {
                $quartersQ->where([
                    'data_status' => 1,
                    'quarters_condition_id' => 3,
                ]);
            },
            'quarters as total_selenggara' => function ($quartersQ) {
                $quartersQ->where([
                    'data_status' => 1,
                    'quarters_condition_id' => 2,
                ]);
            },
            'quarters as total_empty' => function ($quartersQ) {
                $quartersQ->whereDoesntHave('application_quarters_categories')
                    ->whereNotNull('unit_no')
                    ->where('quarters_condition_id', 1)
                    ->where('data_status', 1);
            },
            'quarters as total_left' => function ($quartersQ) {
                $quartersQ->whereHas('latest_tenant', function ($tenantQ) {
                    $tenantQ->where('data_status', 1)
                        ->where('leave_status_id', 1)
                        ->where('leave_date', '<', Carbon::now());
                })
                    ->where('quarters_condition_id', 1)
                    ->where('data_status', 1);
            },
        ])
            ->where('data_status', 1);

        return $data;
    }

    public static function getQuartersCategoryByGradeRange41($districtId, $gradeRange, $landedType, $categoryId = null)
    {
        $quartersCategories = self::where('data_status', 1)
            ->whereHas('district', function ($q) use ($districtId) {
                $q->where('id', $districtId);
            });

        if ($gradeRange) {
            $quartersCategories->whereHas('class', function ($q) use ($gradeRange) {
                $q->whereHas('quartersClass', function ($q2) use ($gradeRange) {
                    $q2->whereHas('class_grade', function ($q3) use ($gradeRange) {
                        $q3->whereHas('positionGrade', function ($q4) use ($gradeRange) {
                            $q4->where('grade_no', $gradeRange, 41)
                                ->where('data_status', 1);
                        });
                    });
                });
            });
        }

        if ($landedType) {
            $quartersCategories = $quartersCategories->where('landed_type_id', $landedType);
        }

        if($categoryId) {
            $quartersCategories = $quartersCategories->where('id', $categoryId);
        }
        
        return $quartersCategories->get();
    }

    public static function getAllAgencyWithTenant($year=0, $month=0)
    {
        //SHOW AGENCY WITH TENANT>0
        $data = self::withCount(['tenants_payment_notice as total_tenants' => function($q) use($year, $month) {
                $q->whereYear('notice_date', '=', $year)
                ->whereMonth('notice_date', '=', $month)
                ->where('tenants_payment_notice.district_id', '=', districtId());
            }]) 
            ->having('total_tenants', '>', 0)
            ->orderBy('organization.name')
            ->get();

        return $data;
    }
}
