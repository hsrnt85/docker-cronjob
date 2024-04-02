<?php

    use App\Models\District;
    use App\Models\Epnj;

    // Get data from EPNJ
    function getDataFromEPNJ($noKP)
    {
		if(!$noKP)
        {
			return false;
		}

        $response = Illuminate\Support\Facades\Http::withoutVerifying()
        ->withHeaders(['X-API-KEY' => config('env.epnj_api_key')])
        ->get(config('env.epnj_url'), [
            "noPengenalan" => $noKP,
        ]);

        $data = $response->json();

        $data["noPengenalan"] = $noKP;

        return ($data["response_code"] == 200) ? $data : false;
    }

    // Insert EPNJ
    function insertDataEPNJ($EPNJ)
    {
        $data = $EPNJ['data'][0];

        $epnj = Epnj::firstOrCreate([
            'ic' => $EPNJ['noPengenalan'],
            'ownership_no' => $data['no_hakmilik'],
            'data_status' => 1,
        ],[
            'is_epnj' => 1,
            'state' => $data['negeri'],
            'district_id' => getDistrictId($data['daerah']),
            'mukim' => $data['mukim'],
            'lot_type' => $data['jenis_lot'],
            'lot_no' => $data['no_lot'],
            'house_type' => $data['jenis_rumah'],
            'loan_type' => $data['jenis_pinjaman'],
            'action_on' => currentDate()
        ]);
    }

    function getDistrictId($district)
    {
        $data = District::where('district_name', $district)->first();
        return $data->id;
    }

?>
