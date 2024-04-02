<?php
    use App\Models\Department;
    use App\Models\District;
    use App\Models\Position;
    use App\Models\PositionType;
    use App\Models\PositionGrade;
    use App\Models\PositionGradeType;
    use App\Models\ServicesType;
    use App\Models\User;
    use App\Models\UserSpouse;
    use App\Models\UserHouse;
    use App\Models\UserChild;
    use App\Models\UserInfo;
    use App\Models\MaritalStatus;
    use App\Models\Organization;
    use App\Models\UserOffice;
    use Illuminate\Support\Facades\Hash;

    // Get data from HRMIS
    function getDataFromHRMIS($noKP)
    {
		if($noKP){
            $data['table']['request'] = [
                'UserID' => config('env.hrmis_user_id'),
                'NoKP' => $noKP,
            ];


            $response = Illuminate\Support\Facades\Http::withoutVerifying()
            ->withHeaders(['X-API-KEY' => config('env.hrmis_api_key')])
            ->withBody(json_encode($data), 'application/json')
            ->post(config('env.hrmis_url'));

			$data = $response->json();


			if($data['table']['response']['StatusAksesPengguna']['StatusUserID'] == 1)
			{
				return $response->json();
			}
			else
			{
				return false;
			}
		}else{
			return false;
		}

    }

    function insertDataHRMIS($HRMIS)
    {
        $peribadi       = $HRMIS['table']['response']['MaklumatPeribadi'];
        $perkhidmatan   = $HRMIS['table']['response']['MaklumatPerkhidmatan'];

        $namaJawatanArr = explode(',', $perkhidmatan['NamaJawatan']);
        $namaJawatan = strtoupper($namaJawatanArr[0] ?? "");
        $position = Position::firstOrCreate([
            'position_name' => $namaJawatan,
            'data_status' => 1,
        ],[
            'action_on' => currentDate()
        ]);

        $positionType = PositionType::where([
            'position_code' => $perkhidmatan['KodStatusLantikan'],
            'data_status' => 1,
        ])
        ->first();

        $gradeKuarters = splitGradeHRMIS($perkhidmatan['GredGajiSemasa']);

        $positionGrade = PositionGrade::firstOrCreate([
            'grade_no' => $gradeKuarters['grade'],
            'data_status' => 1,
        ],[
            'action_on' => currentDate()
        ]);

        $positionGradeCode = PositionGradeType::firstOrCreate([
            'grade_type' => $gradeKuarters['code'],
            'data_status' => 1,
        ],[
            'action_on' => currentDate()
        ]);

        $servicesType = ServicesType::firstOrCreate([
            'code' => $perkhidmatan['KodKumpulanAgensi'],
            'data_status' => 1,
        ],[
            'action_on' => currentDate()
        ]);

        $maritalStatus = MaritalStatus::firstOrCreate([
            'code' => $peribadi['KodStatusPerkahwinan'],
            'data_status' => 1,
        ],[
            'action_on' => currentDate()
        ]);

        $user = User::firstOrCreate([
            'new_ic' => $peribadi['NoKP'],
            'data_status' => 1,
        ],[
            'name' => $peribadi['Nama'],
            'position_id' => $position->id,
            'position_type_id' => $positionType->id,
            'position_grade_id' => $positionGrade->id,
            'position_grade_code_id' => $positionGradeCode->id,
            'services_type_id' => $servicesType->id,
            'marital_status_id' => $maritalStatus->id,
            'roles_id' => '',
            'password' => Hash::make($peribadi['NoKP']),
            'email' => $peribadi['Emel'],
            'phone_no_hp' => $peribadi['NoTelBimbit'],
            'phone_no_home' => str_replace(' ', '', (implode("",explode("-",$peribadi['NoTelRumah'])))),
            'date_of_service' => date('Y-m-d', strtotime($perkhidmatan['TarikhLantikanPertama'])),
            'expected_date_of_retirement' => date('Y-m-d', strtotime($perkhidmatan['TarikhDiJangkaBesara'])),
            'is_blacklist_application' => 0,
            'is_hrmis' => 1,
            'flag' => 1,
            'data_status' => 2, // need approval
            'action_on' => currentDate(),
        ]);

        //User Info
        $userInfo = UserInfo::firstOrCreate([
            'users_id' => $user?->id,
            'data_status' => 1,
        ],[
            'position_id' => $position->id,
            'position_type_id' => $positionType->id,
            'position_grade_id' => $positionGrade->id,
            'position_grade_code_id' => $positionGradeCode->id,
            'services_type_id' => $servicesType->id,
            'marital_status_id' => $maritalStatus->id,
            'action_on' => currentDate(),
        ]);

        // User office
        $namaAgensiRasmi = strtoupper($perkhidmatan['NamaAgensiRasmi'] ?? "");
        $organization = Organization::firstOrCreate([
            'name' => $namaAgensiRasmi
        ],[
            'action_on' => currentDate()
        ]);

        $unitOrganisasiTerkecil = getDepartmentName(strtoupper($perkhidmatan['UnitOrganisasiTerkecil'] ?? ""));
        $department = Department::firstOrCreate([
            'organization_id' => $organization->id,
            'department_name' => $unitOrganisasiTerkecil,
        ]);

        $officeLatLong = getLatLongByAddress($perkhidmatan['AlamatPejabat1'], $perkhidmatan['AlamatPejabat2'], $perkhidmatan['AlamatPejabat3']);

        $district = getDistrictByPostcode($perkhidmatan['PoskodPejabat']);

        $office = UserOffice::firstOrCreate([
            'users_id' => $user->id
        ], [
            'organization_id' => $organization->id,
            'department_id' => $department->id,
            'address_1' => $perkhidmatan['AlamatPejabat1'],
            'address_2' => $perkhidmatan['AlamatPejabat2'],
            'address_3' => $perkhidmatan['AlamatPejabat3'],
            'district_id' => $district->id,
            'postcode' => $perkhidmatan['PoskodPejabat'],
            'phone_no_office' => str_replace(' ', '', (implode("",explode("-", $perkhidmatan['NoTelPejabat'])))),
            'latitude' => $officeLatLong['lat'],
            'longitude' => $officeLatLong['long'],
            'data_status' => 1,
            'action_on' => currentDate()
        ]);


        // User house address
        $latlongFixedAddress = getLatLongByAddress($peribadi['AlamatTetap1'], $peribadi['AlamatTetap2'], $peribadi['AlamatTetap3']);

        $fixedAddress = UserHouse::firstOrCreate([
            'users_id' => $user->id,
            'address_type' => 1
        ],[
            'address_1' => $peribadi['AlamatTetap1'],
            'address_2' => $peribadi['AlamatTetap2'],
            'address_3' => $peribadi['AlamatTetap3'],
            'latitude'  => $latlongFixedAddress['lat'],
            'longitude' => $latlongFixedAddress['long'],
            'distance'  => getDistanceByLatLong($latlongFixedAddress['lat'], $latlongFixedAddress['long'], $office->latitude, $office->longitude),
            'postcode'  => $peribadi['PoskodTetap'],
            'action_on' => currentDate(),
        ]);

        $latlongMailAddress = getLatLongByAddress($peribadi['AlamatSurat1'], $peribadi['AlamatSurat2'], $peribadi['AlamatSurat3']);

        $mailAddress = UserHouse::firstOrCreate([
            'users_id' => $user->id,
            'address_type' => 2
        ],[
            'address_1' => $peribadi['AlamatSurat1'],
            'address_2' => $peribadi['AlamatSurat2'],
            'address_3' => $peribadi['AlamatSurat3'],
            'latitude'  => $latlongFixedAddress['lat'],
            'longitude' => $latlongFixedAddress['long'],
            'distance'  => getDistanceByLatLong($latlongMailAddress['lat'], $latlongMailAddress['long'], $office->latitude, $office->longitude),
            'postcode'  => $peribadi['PoskodSurat'],
            'action_on' => currentDate(),
        ]);

        // Keluarga
        $jenisAnak = ['05', '06', '07', '08', '15', '20', '21'];
        $maklumatKeluarga   = isset($HRMIS['table']['response']['MaklumatKeluarga']) ? $HRMIS['table']['response']['MaklumatKeluarga'] : null;

        $pasangan = null;
        $anak2 = [];

        if($maklumatKeluarga != null)
        {
            foreach($maklumatKeluarga as $keluarga)
            {
                if(($keluarga['KodHubungan'] == '01' || $keluarga['KodHubungan'] == '02') && $keluarga['KodTanggungan'] == '100')
                {
                    $pasangan = $keluarga;
                }

                if($keluarga['StatusTanggungan'] == '1' && in_array($keluarga['KodHubungan'], $jenisAnak) )
                {
                    array_push($anak2, $keluarga);
                }
            }
        }

        // Error if use db:transaction
        // $user = User::where([
        //     'new_ic' => $peribadi['NoKP'],
        //     'data_status' => 1,
        //     'flag' => 2
        // ])->first();


        if($pasangan)
        {
            UserSpouse::firstOrCreate([
                'users_id' => $user->id,
                'new_ic' => $pasangan['NoKP'],
            ],[
                'spouse_name' => $pasangan['NamaKeluarga'],
                'data_status' => 1,
                'action_on' => currentDate(),
            ]);

        }

        if($anak2)
        {
            foreach($anak2 as $anak)
            {
                UserChild::firstOrCreate([
                    'users_id' => $user->id,
                    'new_ic' => $anak['NoKP'],
                ],[
                    'child_name' => $anak['NamaKeluarga'],
                    'birth_cert' => $anak['SuratBeranak'],
                    'is_cacat' => ($anak['StatusKecacatan'] == 'Ya') ? 1: 0,
                    'data_status' => 1,
                    'action_on' => currentDate(),
                ]);
            }
        }

        return $user;
    }

    function splitGradeHRMIS($gradeHRMIS)
    {
        $grade['code']  = '';
        $grade['grade'] = '';

        $arr1 = str_split($gradeHRMIS);

        foreach ($arr1 as $value)
        {
            if(!is_numeric($value) && $grade['code'] != 'JUSA' && $grade['code'] != 'TURUS')
            {
                $grade['code'] .= $value;
            }
            else
            {
                $grade['grade'] .= $value;
            }
        }

        return $grade;
    }

    function getDepartmentName($department)
    {
        $departmentArr = explode(',', $department);
        array_pop($departmentArr);
        $result = implode(', ', $departmentArr);

        return $result;
    }

    function getDistrictByPostcode($postcode)
    {
        $data = District::where('data_status', 1)
                ->whereHas('postcodes', function ($query) use ($postcode){
                    $query->where('postcode', $postcode);
                })
                ->first();

        return $data;
    }

    function getLatLongByAddress($address1, $address2, $address3){

        $response = Illuminate\Support\Facades\Http::get(config('env.gis_provider_url'), [
            'q' => $address1 . ' ' . $address2 . ' ' . $address3,
            'format' => 'json',
            'polygon' => '1',
            'addressdetails' => '1'
        ]);

        $data = $response->json();

        $latitude  = 0;
        $longitude = 0;

        if($data != null)
        {
            $latitude   = $data[0]['lat'];
            $longitude  = $data[0]['lon'];
        }

        return [
            'lat' => $latitude,
            'long' => $longitude,
        ];
    }

    // Kira jarak based on latitude
    function getDistanceByLatLong($lat1, $lon1, $lat2, $lon2, $unit = 'K') {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        elseif($lat1 == 0 || $lat2 == 0 || $lon1 == 0 || $lon2 ==0){
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

?>
