<?php

    use App\Http\Resources\GetData;
    use App\Models\District;

    function systemFlag(){
        return 1;
    }

    function loginId(){
        $data = auth()->user()->id;
        return $data;
    }

    function loginData(){
        $data = auth()->user();
        return $data;
    }
    
    function tenantsId(){
        $data = auth('sanctum')->user()?->tenants?->id;
        return $data;
    }

    function districtId(){
        $userOffice = GetData::UserOffice(loginId());
        $data = $userOffice?->district_id;
        return $data;
    }
    
    function district($districtId=''){
        $districtId = (!$districtId) ? districtId():"";
        $data = District::select('district_code','district_name','finance_district_code')->Where('id',$districtId)->first();
        return $data;
    }

    function is_all_district(){
        $data = auth()->user()->roles->is_district;
        return $data;
    }

    function financeDistrictId(){
        return 1;
    }

    function getJataJohor(){
        $path = public_path().'/assets/images/jata-johor.png';
        return $path;
    }

    function getNoImages(){
        $path = URL::asset('assets/images/no-images.png');
        return $path;
    }

    function getPathDocumentCss(){
        $path = public_path() .'/assets/css/document/';
        return $path;
    }

    function getCdn(){
        $cdn = config('env.upload_ftp_url');
        return $cdn;
    }

    function pathAttachment(){
        $path = public_path().'/assets/attachment_kuarters/';
        return $path;
    }

    function getPortalUrl(){
        $url = config('env.portal_url');
        return $url;
    }

    function getAdminUrl(){
        $url = config('env.admin_url');
        return $url;
    }

?>
