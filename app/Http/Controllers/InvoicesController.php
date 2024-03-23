<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use App\Models\Invoices;
use App\Models\Invoices_details;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $invoices = Invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections = Sections::all();
        return view('invoices/add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        invoices::create([
            "invoice_number" => $request->invoice_number,
            "inovices_date" => $request->invoice_Date,
            "due_date" => $request->Due_date,
            "product" => $request->product,
            "section_ID" => $request->Section,
            "Amount_collection" => $request->Amount_collection,
            "Amount_Commission" => $request->Amount_Commission,
            "discount" => $request->Discount,
            "total" => $request->Total,
            "value_vat" => $request->Value_VAT,
            "rate_vat" => $request->Rate_VAT,
            "status" => 'غير مدفوعة',
            "value_status" => 2,
            "note" => $request->note,
        ]);

        $this->saveInvoicesDetails($request);

        $this->saveInvoicesAttachment($request);

        $this->saveImageToPupblicFolder($request);


        $this->saveMeassgToSession('success', 'تم إضافة الفاتورة بنجاح');
        return redirect($this->toThisPage());
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoices $invoices)
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

        $sections  = Sections::all();

        return view('invoices/invoices_edit', compact('sections'))->with("invoices",$invoices);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $invoice = Invoices::findOrFail($request->invoice_id);
        $invoice->update([
            "invoice_number" => $request->invoice_number,
            "inovices_date" => $request->invoice_Date,
            "due_date" => $request->Due_date,
            "product" => $request->product,
            "section_ID" => $request->Section,
            "Amount_collection" => $request->Amount_collection,
            "Amount_Commission" => $request->Amount_Commission,
            "discount" => $request->Discount,
            "total" => $request->Total,
            "value_vat" => $request->Value_VAT,
            "rate_vat" => $request->Rate_VAT,
            "status" => 'غير مدفوعة',
            "value_status" => 2,
            "note" => $request->note,
        ]);
        $this->saveMeassgToSession('success', 'تم تعديل الفاتورة بنجاح');
        return redirect($this->toThisPage());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices $invoices)
    {
        //
    }
    public  function getProducts($id)
    {

        $products = DB::table('products')->where('section_ID',$id)->pluck('name','id');

        return json_encode($products);
    }

    private function  toThisPage()
    {
        return '/invoices';
    }
    private function saveMeassgToSession($type, $message)
    {
        session()->flash($type,$message);

    }
    private function saveInvoicesDetails(Request $request)
    {
        $invoice_ID = invoices::latest()->first()->id;
        Invoices_details::create([
            "invoice_ID" =>$invoice_ID,
            "invoice_number" => $request->invoice_number,
            "product" => $request->product,
            "section" => $request->Section,
            "statuse" => 'غير مدفوعة',
            "value_status" => 2,
            "note" => $request->note,
            "user" => Auth::user()->name,

        ]);
    }
    private function saveImageToPupblicFolder($request)
    {
        $imageName = $request->pic->getClientOriginalName();
        $request->pic->move(public_path('Attachments/'. $request->invoice_number), $imageName);
    }
    private function saveInvoicesAttachment(Request  $request)
    {
        if($request->hasFile('pic'))
        {
            $invoice_ID = invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachment = new Invoice_attachments();
            $attachment->file_name = $file_name;
            $attachment->invoice_number = $invoice_number;
            $attachment->created_by = Auth::user()->name;
            $attachment->invoices_ID = $invoice_ID;
            $attachment->save();

        }
    }

    private function validInvoices(Request $request)
    {
        $this->validate =  $request->validate(
            [
                "invoice_number" => "required|unique:invoices|max:255",
                "Amount_collection" => "required|max:8"
            ],
            [
                "invoice_number.required"  => 'يرجى إدحال رقم الفاتورة',
                "invoice_number.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف في حقل رقم الفاتورة ',
            ]
        );
    }

    public function getInvoicesDetailes($id)
    {
        $InvoDetailes = table('invoices_details')->where("invoice_ID" , $id)->limit(5);
        return view('invoices/invoices_details');
    }
}
