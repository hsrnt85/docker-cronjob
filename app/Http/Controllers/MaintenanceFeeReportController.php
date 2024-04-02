<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandedType;
use App\Models\QuartersCategory;
use App\Models\Quarters;
use App\Models\Tenant;

class MaintenanceFeeReportController extends Controller
{
    //Maintenance Fee By Quarters Category
    public function maintenanceFeeByQuartersCategoryList(){

        //FILTER BY OFFICER DISTRICT ID
        $district_id = (!is_all_district()) ?  districtId() : null;

        $landedTypeAll = LandedType::where('data_status', 1)->get();

        $maintenanceFeebyQuartersCategoryArr = [];

        foreach($landedTypeAll as $i_landedType => $landedType)
        {
            $sumQuarters = 0;
            $sumQuartersWithTenant = 0;
            $sumQuartersWithoutTenant = 0;

            $landed_type_id = $landedType->id;
            $landed_type_name = $landedType->name;

            if($district_id)
            {
                $quartersCategoryAll = QuartersCategory::where(['data_status'=> 1, 'district_id'=> $district_id, 'landed_type_id'=> $landed_type_id])->get();
            }
            else
            {
                $quartersCategoryAll = QuartersCategory::where(['data_status'=> 1, 'landed_type_id'=> $landed_type_id])->get();
            }

            $i=0;
            foreach($quartersCategoryAll as $quartersCategory)
            {
                $sumMaintenanceFee = 0;

                $quarters_category_id = $quartersCategory->id;
                $quarters_category_name = $quartersCategory->name;

                //ALL QUARTERS
                $sumQuarters = Quarters::getAvailableUnitMaintenanceFeeAll($quarters_category_id);

                //AVAILABLE QUARETRS
                $sumQuartersWithoutTenant = Quarters::getEmptyUnitMaintenanceFeeAll($quarters_category_id);

                //QUARETRS WITH TENANT
                $sumQuartersWithTenant = Tenant::getAllCurrentTenantsMaintenanceFeeCountByCategory($quarters_category_id);

                //sum maintenance fee
                $sumMaintenanceFee = Quarters::where(['data_status'=> 1, 'quarters_cat_id'=> $quarters_category_id])->sum('maintenance_fee');

                //If Maintenance Fee > 0.00
                if($sumMaintenanceFee > 0){
                    $maintenanceFeebyQuartersCategoryArr[$i_landedType][$i]['quarters_category_name'] = $quarters_category_name;
                    $maintenanceFeebyQuartersCategoryArr[$i_landedType][$i]['sum_quarters'] = $sumQuarters;
                    $maintenanceFeebyQuartersCategoryArr[$i_landedType][$i]['sum_quarters_with_tenant'] = $sumQuartersWithTenant;
                    $maintenanceFeebyQuartersCategoryArr[$i_landedType][$i]['sum_available_quarters'] = $sumQuartersWithoutTenant;
                    $maintenanceFeebyQuartersCategoryArr[$i_landedType][$i]['sum_maintenance_fee'] = $sumMaintenanceFee;
                    $i++;
                }

            }
        }
        //dd($maintenanceFeebyQuartersCategoryArr);
        return view(getFolderPath().'.index',
            compact('landedTypeAll','maintenanceFeebyQuartersCategoryArr')
        );

    }
}
