<?php

namespace App\Http\Controllers;

use App\Models\IspeksPaymentCode;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
//use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentCategory;
use App\Http\Resources\ListValidateDelete;


class PaymentMethodController extends Controller
{
    //
    public function index()
    {

        $paymentMethodAll = PaymentMethod::where('data_status', 1)->orderBy('id')->orderBy('payment_method')->get();

        return view(getFolderPath().'.list',
        [
            'paymentMethodAll' => $paymentMethodAll,
        ]);
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $paymentMethod = PaymentMethod::findOrFail($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'paymentMethod' => $paymentMethod,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }

    public function create()
    {
        $ispeksPaymentCodes = IspeksPaymentCode::where('data_status', 1)->get();
        $paymentCategoryCode = PaymentCategory::where('data_status', 1)->get();

        if (checkPolicy("A"))
        {
            return view(getFolderPath() . '.create', compact('ispeksPaymentCodes','paymentCategoryCode'));
        } else
        {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }
    }


    public function edit(Request $request)
    {
        $id = $request->id;

        $paymentMethod = PaymentMethod::findOrFail($id);
        $ispeksPaymentCodes = IspeksPaymentCode::where('data_status', 1)->get();
        $paymentCategoryCode = PaymentCategory::where('data_status', 1)->get();


        if(checkPolicy("U"))
        {
            return view( getFolderPath().'.edit', compact('paymentMethod','ispeksPaymentCodes','paymentCategoryCode'));
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method_code' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'ispeks_payment_code_id' => 'required|exists:ispeks_payment_code,id',
            'payment_category_id' => 'required|exists:payment_category,id',

        ]);

        $paymentMethod = new PaymentMethod($request->all());
        $paymentMethod->data_status = 1;
        $paymentMethod->save();


        //------------------------------------------------------------------------------------------------------------------
        // Save User Activity
        //------------------------------------------------------------------------------------------------------------------
        setUserActivity("A", $paymentMethod->payment_method);
        //------------------------------------------------------------------------------------------------------------------


        return redirect()->route('paymentMethod.index')->with('success', 'Maklumat Kaedah Pembayaran berjaya disimpan!');
    }

    public function update(PaymentMethodRequest $request)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($request->id);

            // User Activity - Set Data before changes
            $data_before = $paymentMethod->toArray();

            $paymentMethod->payment_method_code = $request->payment_method_code;
            $paymentMethod->payment_method = $request->payment_method;
            $paymentMethod->payment_category_id = $request->payment_category_id;
            $paymentMethod->ispeks_payment_code_id = $request->ispeks_payment_code_id;
            $paymentMethod->action_by    = loginId();
            $paymentMethod->action_on    = currentDate();
            $updated = $paymentMethod->save();

            $data_after = $paymentMethod->toArray(); //get data after
            setUserActivity("U", $paymentMethod->payment_method, $data_before, $data_after);  //save data


            if ($updated) {
                return redirect()->route('paymentMethod.index')->with('success', 'Maklumat Kaedah Pembayaran berjaya dikemaskini!');
            }
        } catch (\Exception $e) {
            // Something went wrong
            return redirect()->route('paymentMethod.edit', ['id' => $request->id])->with('error', 'Maklumat Kaedah Pembayaran tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
    }


    public function destroy(Request $request)
    {

        DB::beginTransaction();

        try {
            $paymentMethod = PaymentMethod::findOrFail($request->id);

            setUserActivity("D", $paymentMethod->payment_method);

            $paymentMethod->data_status   = 0;
            $paymentMethod->delete_by     = loginId();
            $paymentMethod->delete_on     = currentDate();
            $paymentMethod->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('paymentMethod.index')->with('error', 'Maklumat Kaedah Bayaran tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('paymentMethod.index')->with('success', 'Maklumat Kaedah Bayaran berjaya dihapus!');
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validatePaymentMethod($id);
        return response()->json($data);
    }

}
