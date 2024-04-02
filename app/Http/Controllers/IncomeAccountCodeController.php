<?php

namespace App\Http\Controllers;

use App\Models\IncomeAccountCode;
use App\Models\PaymentCategory;
use App\Http\Requests\IncomeAccountCodeRequest;
use App\Http\Resources\ListData;
use App\Http\Resources\ListValidateDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeAccountCodeController extends Controller
{
    public function index()
    {

        $incomeAccountCodeAll = IncomeAccountCode::where('data_status', 1)->orderBy('account_type_id')->orderBy('payment_category_id')->get();

        return view(getFolderPath().'.list',
        [
            'incomeAccountCodeAll' => $incomeAccountCodeAll,
        ]);
    }

    public function create()
    {
        $paymentCategoryAll = ListData::PaymentCategory();
        $accountTypeAll = ListData::AccountType();
        $servicesStatusAll = ListData::ServicesStatus();

        if(checkPolicy("A"))
        {
            return view(getFolderPath().'.create', compact('paymentCategoryAll', 'accountTypeAll','servicesStatusAll'));
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(Request $request)//IncomeAccountCode
    {
        try {
            $paymentCategory = PaymentCategory::select('flag_ispeks')->where('id', $request->payment_category)->pluck('flag_ispeks');
            $implode_services_status  = $request->input('services_status') != null ? ArrayToString($request->input('services_status')) : "";
            $flag_outstanding = ($request->flag_outstanding) ? $request->flag_outstanding : 1;

            $incomeAccountCode = new IncomeAccountCode;
            $incomeAccountCode->salary_deduction_code = $request->salary_deduction_code;
            $incomeAccountCode->general_income_code = $request->general_income_code;
            $incomeAccountCode->ispeks_account_code = $request->ispeks_account_code;
            $incomeAccountCode->ispeks_account_description= $request->ispeks_account_description;
            $incomeAccountCode->income_code = $request->income_code;
            $incomeAccountCode->income_code_description= $request->income_code_description;
            $incomeAccountCode->account_type_id = $request->account_type;
            $incomeAccountCode->payment_category_id = $request->payment_category;
            $incomeAccountCode->flag_outstanding = $flag_outstanding;
            $incomeAccountCode->services_status_id = $implode_services_status;
            $incomeAccountCode->flag_ispeks = $paymentCategory['0'] ?? 0;
            $incomeAccountCode->action_by = loginId();
            $incomeAccountCode->action_on = currentDate();

            $saved = $incomeAccountCode->save();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A",  $incomeAccountCode->income_code = $request->income_code);
            //------------------------------------------------------------------------------------------------------------------


            if($saved) return redirect()->route('incomeAccountCode.index')->with('success', 'Maklumat Vot Hasil berjaya ditambah! ');

        } catch (\Exception $e) {

            // something went wrong
            return redirect()->route('incomeAccountCode.create')->with('error', 'Maklumat Vot Hasil tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $incomeAccountCode = IncomeAccountCode::findOrFail($id);
        $paymentCategoryAll = ListData::PaymentCategory();
        $accountTypeAll = ListData::AccountType();
        $servicesStatusAll = ListData::ServicesStatus();

        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit', compact('paymentCategoryAll', 'accountTypeAll','servicesStatusAll','incomeAccountCode'));
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function update(Request $request)//IncomeAccountCode
    {
        $id = $request->id;

        try {
            $paymentCategory = PaymentCategory::select('flag_ispeks')->where('id', $request->payment_category)->pluck('flag_ispeks');
            $implode_services_status  = $request->input('services_status') != null ? ArrayToString($request->input('services_status')) : "";
            $flag_outstanding = ($request->flag_outstanding) ? $request->flag_outstanding : 1;

            $incomeAccountCode = IncomeAccountCode::findOrFail($id);
            
            // $data_before = [];
            // $data_before['salary_deduction_code'] = $incomeAccountCode->salary_deduction_code;
            // $data_before['general_income_code'] = $incomeAccountCode->general_income_code;
            
            $incomeAccountCode->salary_deduction_code = $request->salary_deduction_code;
            $incomeAccountCode->general_income_code = $request->general_income_code;
            $incomeAccountCode->ispeks_account_code = $request->ispeks_account_code;
            $incomeAccountCode->ispeks_account_description= $request->ispeks_account_description;
            $incomeAccountCode->income_code = $request->income_code;
            $incomeAccountCode->income_code_description= $request->income_code_description;
            $incomeAccountCode->account_type_id = $request->account_type;
            $incomeAccountCode->payment_category_id = $request->payment_category;
            $incomeAccountCode->flag_outstanding = $flag_outstanding;
            $incomeAccountCode->services_status_id = $implode_services_status;
            $incomeAccountCode->flag_ispeks = $paymentCategory['0'] ?? 0;
            $incomeAccountCode->action_by = loginId();
            $incomeAccountCode->action_on = currentDate();
            $updated = $incomeAccountCode->save();

            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            // $data_after = $incomeAccountCode;
            // $data_after['item'] = $incomeAccountCode->toArray() ?? [];

            // $data_before_json = json_encode($data_before);
            // $data_after_json = json_encode($data_after);
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            //setUserActivity("U", $incomeAccountCode->name, $data_before_json, $data_after_json);
            //------------------------------------------------------------------------------------------------------------------


            if($updated) return redirect()->route('incomeAccountCode.index')->with('success', 'Maklumat Vot Hasil berjaya dikemaskini!');
                  

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('incomeAccountCode.edit', ['id'=>$request->id])->with('error', 'Maklumat Vot Hasil tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $servicesStatusAll = ListData::ServicesStatus();
        $incomeAccountCode = IncomeAccountCode::findOrFail($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view', compact('servicesStatusAll','incomeAccountCode'));
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $incomeAccountCode = IncomeAccountCode::findOrFail($request->id);

            setUserActivity("D",  $incomeAccountCode->income_code);

            $incomeAccountCode->data_status   = 0;
            $incomeAccountCode->delete_by     = loginId();
            $incomeAccountCode->delete_on     = currentDate();
            $incomeAccountCode->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('incomeAccountCode.index')->with('error', 'Maklumat Vot Hasil tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('incomeAccountCode.index')->with('success', 'Maklumat Vot Hasil berjaya dihapus!');
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateIncomeAccountCode($id);
        return response()->json($data);
    }

}
