<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\ListValidateDelete;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index()
    {

        $documentAll = Document::where('data_status', 1)->get();

        return view(getFolderPath().'.list',
        [
            'documentAll' => $documentAll
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


    public function store(DocumentRequest $request)
    {
        DB::beginTransaction();

        try {
            $document = new Document;
            $document->document_name  = $request->document;
            $document->action_by   = loginId();
            $document->action_on   = currentDate();

            $document->save();

            //------------------------------------------------------------------------------------------------------------------
            // Save User Activity
            //------------------------------------------------------------------------------------------------------------------
            setUserActivity("A", $document->document_name);
            //------------------------------------------------------------------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('document.create')->with('error', 'Dokumen tidak berjaya ditambah!' . ' ' . $e->getMessage());
        }

        return redirect()->route('document.index')->with('success', 'Dokumen berjaya ditambah! ');
    }


    public function view(Request $request)
    {
        $id = $request->id;

        $document = Document::findOrFail($id);

        if(checkPolicy("V"))
        {
            return view(getFolderPath().'.view',
            [
                'document' => $document
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;

        $document = Document::findOrFail($id);

        if(checkPolicy("U"))
        {
            return view(getFolderPath().'.edit',
            [
                'document' => $document
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('error-permission','access.denied');
        }

    }


    public function update(DocumentRequest $request)
    {
        $id = $request->id;
        

        DB::beginTransaction();

        try {
            $document = Document::findOrFail($id);

            // User Activity - Set Data before changes
            $data_before = $document->getRawOriginal();
    
            $document->document_name  = $request->document;
            $document->action_by   = loginId();
            $document->action_on   = currentDate();

            $document->save();
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Set Data after changes
            $data_after = $document;
            $data_before_json = json_encode($data_before);
            $data_after_json = json_encode($data_after);
            //------------------------------------------------------------------------------------------------------------------
            // User Activity - Save
            setUserActivity("U", $document->document_name, $data_before_json, $data_after_json);
            //------------------------------------------------------------------------------------------------------------------

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // something went wrong
            return redirect()->route('document.edit', ['id'=>$request->id])->with('error', 'Dokumen tidak berjaya dikemaskini!' . ' ' . $e->getMessage());
        }

        return redirect()->route('document.index')->with('success', 'Dokumen berjaya dikemaskini!');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {
            $document = Document::findOrFail($id);

            setUserActivity("D", $document->document_name);

            $document->data_status       = 0;
            $document->delete_by         = loginId();
            $document->delete_on         = currentDate();
            $document->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('document.edit', ['id'=>$request->id])->with('error', 'Dokumen tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

        return redirect()->route('document.index')->with('success', 'Dokumen berjaya dihapus!');
    }

    public function validateDelete(Request $request){
        $id = $request->id;
        $data = ListValidateDelete::validateDocument($id);
        return response()->json($data);
    }

}
