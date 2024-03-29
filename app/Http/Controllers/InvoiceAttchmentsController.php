<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttchmentsController extends Controller
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
    public function __construct()
    {
        $this->middleware('permission:اضافة مرفق',['only'=>['create','store']]);
    }
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
        $this->validate($request,[
            "file_name" => "mimes:pdf,jpeg,png,jpg"
        ],[
            "file_name.mimes" => "صيغة المرفق يجب أن تكون pdf,jpeg,png,jpg"
        ]);

        $image = $request->file('file_name');
        $file_name = $image->getClientOriginalName();

        $attachment = new Invoice_attachments();
        $attachment->file_name = $file_name;
        $attachment->invoices_ID = $request->invoice_id;
        $attachment->invoice_number = $request->invoice_number;

        $attachment->Created_by = Auth::user()->name;
        $attachment->save();

        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'. $request->invoice_number), $imageName);
        session()->flash('success', 'تم إضافة المرفق بنجاح');
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_attachments $invoice_attchments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attachments $invoice_attchments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_attachments $invoice_attchments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice_attachments $invoice_attchments)
    {
        //
    }
    private function saveMeassgToSession($type, $message)
    {
        session()->flash($type,$message);

    }
}
