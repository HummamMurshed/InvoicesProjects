<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تعديل الفاتورة',['only' => ['edit','update']]);
    }
    public function index()
    {
        //
        $invoices =  Invoices::onlyTrashed()->get();
        return view('invoices/archive_invoices')->with('invoices',$invoices);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, )
    {
        //
        $invoices = Invoices::withTrashed()->where('id', $request->invoice_id)->restore();
        $this->saveMeassgToSession('success', 'تم إلغاء أرشفة الفاتورة بنجاح');
        return redirect('invoices');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $invoice = Invoices::withTrashed()->where('id', $request->invoice_id)->first();

        $invoice_Attatchments = Invoice_attachments::where('invoices_ID', $request->invoice_id )->first();


        if(!empty($invoice_Attatchments->invoice_number))
        {

            //Delete Directory
            Storage::disk('public_uploads')->deleteDirectory($invoice_Attatchments->invoice_number);
        }

        $invoice->forceDelete();
        $this->saveMeassgToSession('success', 'تم حذف الفاتورة بنجاح');

        return redirect('invoices_archive');

    }

    private function saveMeassgToSession($type, $message)
    {
        session()->flash($type,$message);

    }
}
