<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationDate;
use App\Http\Requests\ApplicationDatePostRequest;
use \Carbon\Carbon;

class ApplicationDateController extends Controller
{
    public function index()
    {


        $listApplicationDate = ApplicationDate::where('data_status', 1)->orderBy('date_open')->get();

        return view(getFolderPath().'.list',
        [
            'listApplicationDate' => $listApplicationDate
        ]);
    }

    public function create()
    {

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create');
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function store(ApplicationDatePostRequest $request)
    {
        $year       = $request->year;
        $dateOpen   = Carbon::createFromFormat('d/m/Y',  $request->date_open);
        $dateClose  = Carbon::createFromFormat('d/m/Y',  $request->date_close);

        $check = ApplicationDate::where([
            ['year', '=', $year],
            ['data_status', '=', '1']
        ])->first();

        if($check)
        {
            return redirect()->route('applicationDate.create')->with('error', 'Tahun Permohonan sudah wujud!');

        }

        else
        {
            $applicationDate = new ApplicationDate;

            $applicationDate->year         = $year;
            $applicationDate->date_open    = $dateOpen->format('Y-m-d');
            $applicationDate->date_close   = $dateClose->format('Y-m-d');
            $applicationDate->action_by    = loginId();
            $applicationDate->action_on    = currentDate();

            $saved = $applicationDate->save();

             
            // Save User Activity
            setUserActivity("A", $applicationDate->year);


            if(!$saved)
            {
                return redirect()->route('applicationDate.create')->with('error', 'Tarikh Permohonan tidak berjaya ditambah!');
            }
            else
            {
                return redirect()->route('applicationDate.index')->with('success', 'Tarikh Permohonan berjaya ditambah!');
            }
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $applicationDate = ApplicationDate::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'applicationDate' => $applicationDate
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function view(Request $request)
    {
        $id = $request->id;
        $applicationDate = ApplicationDate::where('id', $id)->first();

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'applicationDate' => $applicationDate
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function update(ApplicationDatePostRequest $request)
    {
        $applicationDate = ApplicationDate::where('id', $request->id)->first();

        
        // User Activity - Set Data before changes
        $data_before = $applicationDate->getRawOriginal();//dd($data_before);

        $year = $request->year;
        $dateOpen = $request->date_open;
        $dateClose = $request->date_close;

        $check = ApplicationDate::where([
            ['date_open', '=', $dateOpen],
            ['date_close', '=', $dateClose],
            ['data_status', '=', '1']
        ])->first();

        if($check)
        {
            return redirect()->route('applicationDate.edit', ['id'=>$request->id])->with('error', 'Tarikh Buka dan Tarikh Tutup Permohonan sudah wujud!');
        }

        else
        {
            $dateOpen   = Carbon::createFromFormat('d/m/Y',  $request->date_open);
            $dateClose  = Carbon::createFromFormat('d/m/Y',  $request->date_close);
            $applicationDate->year         = $year;
            $applicationDate->date_open    = $dateOpen->format('Y-m-d');;
            $applicationDate->date_close   = $dateClose->format('Y-m-d');;
            $applicationDate->action_by    = loginId();
            $applicationDate->action_on    = currentDate();

            $saved = $applicationDate->save();

            // User Activity - Set Data after changes
            $data_after = $applicationDate;
            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);

            // User Activity - Save
            setUserActivity("U", $year, $data_before_json, $data_after_json);


            if(!$saved)
            {
                return redirect()->route('applicationDate.edit', ['id'=>$request->id])->with('error', 'Tarikh Permohonan tidak berjaya dikemaskini!');
            }
            else
            {
                return redirect()->route('applicationDate.index')->with('success', 'Tarikh Permohonan berjaya dikemaskini!');
            }
        }
    }

    public function destroy(Request $request)
    {
        $applicationDate = ApplicationDate::where('id', $request->id)->first();

        setUserActivity("D", $applicationDate->year);

        $applicationDate->data_status  = 0;
        $applicationDate->delete_by    = loginId();
        $applicationDate->delete_on    = currentDate();

        $deleted = $applicationDate->save();

        //$deleted = Radius::find($request->id)->delete();

        if(!$deleted)
        {
            return redirect()->route('applicationDate.edit', ['id'=>$request->id])->with('error', 'Tarikh Permohonan tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('applicationDate.index')->with('success', 'Tarikh Permohonan berjaya dihapus!');
        }
    }

}
