<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use App\Models\Invoices;
use App\Models\Invoices_details;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;


class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //

        $invoices = Invoices::where('id', $id)->first();
        $details =  Invoices_details::where('invoice_ID', $id)->get();
        $attachments  = Invoice_attachments::where('invoices_ID', $id)->get();

        return view('invoices/invoices_details', compact(["invoices","details","attachments"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //

        $invoice = Invoice_attachments::findOrFail($request->id_file);
        $invoice->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);
        session()->flash('success','تم حذف المرفق بنجاح');
        return back();


    }
    public function getFile($invoice_number,$file_name)
    {

        $myfileName = 'Attachments';
        $pathFile = public_path($myfileName. '/' . $invoice_number . '/'.$file_name);;
        return response()->download($pathFile);
    }

    public function openFile($invoice_number,$file_name)
    {
        $myfileName = 'Attachments';
        $pathFile = public_path($myfileName. '/' . $invoice_number . '/'.$file_name);;
        return response()->file($pathFile);
    }
}
