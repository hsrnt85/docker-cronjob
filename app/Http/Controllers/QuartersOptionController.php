<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\QuartersOptionRequest;
use App\Models\QuartersOption;

class QuartersOptionController extends Controller
{
    public function index()
    {


        $quartersOptionAll = QuartersOption::where('data_status', 1)->orderBy('option_no')->get();

        return view( getFolderPath().'.list',
        [
            'QuartersOptionAll' => $quartersOptionAll
        ]);
    }

    public function create()
    {
        $quartersOption = QuartersOption::where('data_status', 1)->get();

        if(checkPolicy("A"))
        {
            return view( getFolderPath().'.create',
            [
                'QuartersOption' => $quartersOption
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }


    }

    public function store(QuartersOptionRequest $request)
    {

        $quartersOption = new QuartersOption;

        $quartersOption->option_no        = $request->option_no;
        $quartersOption->execution_date   = $request->execution_date;
        $quartersOption->action_by    = loginId();
        $quartersOption->action_on    = currentDate();

        $saved = $quartersOption->save();

        // Save User Activity
        setUserActivity("A", $quartersOption->option_no);

        if(!$saved)
        {
            return redirect()->route('quartersOption.create')->with('error', 'Bilangan Pilihan Kategori Kuarters (Lokasi) tidak berjaya ditambah!');
        }
        else
        {
            return redirect()->route('quartersOption.index')->with('success', 'Bilangan Pilihan Kategori Kuarters (Lokasi) berjaya ditambah!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $quartersOption = QuartersOption::where('id', $id)->first();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit',
            [
                'quartersOption' => $quartersOption
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(QuartersOptionRequest $request)
    {

        $quartersOption = QuartersOption::where('id', $request->id)->first();
        $quartersOption = QuartersOption::findOrFail($request->id);

        // User Activity - Set Data before changes
        $data_before = $quartersOption->getRawOriginal();//dd($data_before);

        $quartersOption->option_no    = $request->option_no;
        // Add a conditional check for execution_date
        if ($request->has('execution_date')) {
            $quartersOption->execution_date = $request->execution_date;
        }
        // $quartersOption->execution_date   = $request->execution_date;
        
        $quartersOption->action_by    = loginId();
        $quartersOption->action_on    = currentDate();

        $saved = $quartersOption->save();

        // User Activity - Set Data after changes
        $data_after = $quartersOption->toArray();
        $data_before_json = json_encode($data_before);
        $data_after_json = json_encode($data_after);

        // User Activity - Save
        setUserActivity("U", $quartersOption->option_no, $data_before_json, $data_after_json);


        if(!$saved)
        {
            return redirect()->route('quartersOption.edit', ['id'=>$request->id])->with('error', 'Bilangan Pilihan Kategori Kuarters (Lokasi) tidak berjaya dikemaskini!');
        }
        else
        {
            return redirect()->route('quartersOption.index')->with('success', 'Bilangan Pilihan Kategori Kuarters (Lokasi) berjaya dikemaskini!');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;

        $quartersOption = QuartersOption::where('id', $id)->first();

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'quartersOption' => $quartersOption
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function destroy(Request $request)
    {
        $quartersOption = QuartersOption::where('id', $request->id)->first();

        setUserActivity("D", $quartersOption->option_no);

        $quartersOption->data_status  = 0;
        $quartersOption->delete_by    = loginId();
        $quartersOption->delete_on    = currentDate();

        $deleted = $quartersOption->save();

        if(!$deleted)
        {
            return redirect()->route('quartersOption.edit', ['id'=>$request->id])->with('error', 'Bilangan Pilihan Kategori Kuarters (Lokasi) tidak berjaya dihapus!');
        }
        else
        {
            return redirect()->route('quartersOption.index')->with('success', 'Bilangan Pilihan Kategori Kuarters (Lokasi) berjaya dihapus!');
        }
    }


}
