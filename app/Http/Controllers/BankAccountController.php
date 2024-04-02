<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\PaymentCategory;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BankAccountController extends Controller
{
    public function index()
    {

        $bankAccountAll = BankAccount::where('data_status', 1)->get();

        return view(getFolderPath().'.list',
        [
            'bankAccountAll' => $bankAccountAll
        ]);

    }

    public function view(Request $request)
    {
        $id = $request->id;
        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount = BankAccount::with('paymentMethod', 'paymentCategory')->find($id);

        if(checkPolicy("V"))
        {
            return view( getFolderPath().'.view',
            [
                'bankAccount' => $bankAccount,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function create()
    {
        $bankName = Bank::where('data_status', 1)->get();
        $paymentMethod = PaymentMethod::where('data_status', 1)->get();
        $paymentCategory = PaymentCategory::where('data_status', 1)->get();
        $bankAccountType = BankAccountType::where('data_status', 1)->get();

        if (checkPolicy("A")) 
        {
            return view(getFolderPath() . '.create', compact('bankName','paymentMethod','paymentCategory','bankAccountType'));
        } 
        else 
        {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $bankAccount = BankAccount::with('paymentCategory')->find($id);
        $bankName = Bank::where('data_status', 1)->get();
        $paymentMethod = PaymentMethod::where('data_status', 1)->get();
        $paymentCategory = PaymentCategory::find($bankAccount->payment_category_id);
        $paymentCategoryValue = $paymentCategory ? $paymentCategory->payment_category : '';

        if (checkPolicy("U")) {
            return view(getFolderPath() . '.edit', compact('bankAccount', 'bankName', 'paymentMethod', 'paymentCategory', 'paymentCategoryValue'));
        } else {
            return redirect()->route('dashboard')->with('error-permission', 'access.denied');
        }
    }

    public function store(Request $request)
    {
        // Validate the form
        $validatedData = $request->validate([
            'bank_id' => 'required',
            'account_no' => 'required',
            'account_name' => 'required',
            'payment_method_id' => 'required',
            'bank_account_type' => 'required',
        ]);

        $paymentMethodId = $validatedData['payment_method_id'];
        $paymentCategoryId = PaymentMethod::find($paymentMethodId)->payment_category_id;

        // Create a new bank account record using the Eloquent model
        $bankAccount = new BankAccount([
            'bank_id' => $validatedData['bank_id'],
            'account_no' => $validatedData['account_no'],
            'account_name' => $validatedData['account_name'],
            'payment_method_id' => $paymentMethodId,
            'payment_category_id' => $paymentCategoryId,
            'bank_account_type' => $validatedData['bank_account_type'],
        ]);

        $bankAccount->save();

        // Save User Activity
        setUserActivity("A", $bankAccount->account_name);

        // Redirect to a specific route after successful creation
        return redirect()->route('bankAccount.index')->with('success', 'Maklumat akaun bank berjaya disimpan .');
    }

    public function update(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'bank_id' => 'required',
            'account_no' => 'required',
            'account_name' => 'required',
            'payment_method_id' => 'required',
            'bank_account_type' => 'required',
        ]);

        try {
            
            $id = $request->id;
            $bankAccount = BankAccount::findOrFail($id);

            $data_before = $bankAccount->toArray();

            // Set the attributes of the bank account model
            $bankAccount->bank_id = $validatedData['bank_id'];
            $bankAccount->account_no = $validatedData['account_no'];
            $bankAccount->account_name = $validatedData['account_name'];
            $bankAccount->payment_method_id = $validatedData['payment_method_id'];

            // Retrieve payment category id based on payment method id
            $paymentCategoryId = PaymentMethod::find($validatedData['payment_method_id'])->payment_category_id;
            $bankAccount->payment_category_id = $paymentCategoryId;

            $bankAccount->bank_account_type = $validatedData['bank_account_type'];
            $bankAccount->action_by = loginId();
            $bankAccount->action_on = currentDate();

            // Save the updated bank account record
            $updated = $bankAccount->save();

            $data_after = $bankAccount->toArray(); //get data after

            setUserActivity("U", $bankAccount->account_name, $data_before, $data_after);
    
            if ($updated) {
                return redirect()->route('bankAccount.index')->with('success', 'Maklumat Akaun Bank berjaya dikemaskini!');
            } else {
                return redirect()->route('bankAccount.edit', ['id' => $id])->with('error', 'Maklumat Akaun Bank tidak berjaya dikemaskini!');
            }
        } catch (\Exception $e) {
            // Something went wrong
            return redirect()->route('bankAccount.edit', ['id' => $id])->with('error', 'Maklumat Akaun Bank tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $bankAccount = bankAccount::findOrFail($request->id);

            setUserActivity("D", $bankAccount->account_name);

            $bankAccount->data_status   = 0;
            $bankAccount->delete_by     = loginId();
            $bankAccount->delete_on     = currentDate();
            $bankAccount->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('bankAccount.index')->with('error', 'Maklumat Akaun Bank tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('bankAccount.index')->with('success', 'Maklumat Akaun Bank berjaya dihapus!');
    }

    public function getPaymentCategories(Request $request)
    {
        $paymentCategoryId = $request->input('paymentCategoryId');
        $paymentCategory = PaymentCategory::where('id', $paymentCategoryId)->first();
        return response()->json(['paymentCategory' => $paymentCategory]);
    }
}

