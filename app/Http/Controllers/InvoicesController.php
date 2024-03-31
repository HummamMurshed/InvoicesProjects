<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;

use App\Models\Invoice_attachments;
use App\Models\Invoices;
use App\Models\Invoices_details;
use App\Models\Sections;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Mail\Attachment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use function Symfony\Component\String\b;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:قائمة الفواتير',['only' => ['index']]);
        $this->middleware('permission:اضافة فاتورة',['only'=>['create','store']]);
        $this->middleware('permission:تعديل الفاتورة',['only' => ['edit','update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['show','status_update']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['Print_invoice']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['invoicesPaid']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['invoicesUnpaid']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['invoicesPartialPaid']]);

        $this->saveMeassgToSession('NULL',"");
    }
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
        $user =  User::first();
//        $user->notify(new AddInvoice(invoices::latest()->first()->id));
//        Notification::send($user,new AddInvoice(invoices::latest()->first()->id) );
        return redirect($this->toThisPage());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $invoices = Invoices::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }
    public function status_update(Request $request,$id)
    {

        $invoices =  Invoices::findOrfail($request->id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoices_details::create([
                'invoice_ID' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'statuse' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'value_status' => 3,
                'status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            Invoices_details::create([
                'invoice_ID' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'statuse' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }


        $this->saveMeassgToSession('success', 'تم تعديل حالة الدفع بنجاح');
        return redirect($this->toThisPage());
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
    public function destroy(Request $request)
    {
        //
       $invoice = Invoices::where('id',$request->invoice_id )->first();
        $invoice_Attatchments = Invoice_attachments::where('invoices_ID', $request->invoice_id )->first();

       if($request->id_page == 2)
       {

           if(!empty($invoice_Attatchments->invoice_number))
           {
               $this->deleteInvoiceAttachments($request->invoice_id);
               //Delete Directory
               Storage::disk('public_uploads')->deleteDirectory($invoice_Attatchments->invoice_number);
           }

           $invoice->forceDelete();
           $this->saveMeassgToSession('success', 'تم حذف الفاتورة بنجاح');
           return redirect($this->toThisPage());

       }
       else
       {
           $invoice->delete();
           $this->saveMeassgToSession('success', 'تم أرشفة الفاتورة بنجاح');
           return redirect('invoices_archive');
       }



    }
    public function Print_invoice($id)
    {
        $invoices =  Invoices::where('id',  $id)->first();
        return view('invoices.Print_invoice', compact('invoices'));
    }

    public function invoicesPaid()
    {
        $invoices =  Invoices::where('value_status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }
    public function invoicesUnpaid()
    {
        $invoices = Invoices::where('value_status',2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));

    }
    public function invoicesPartialPaid()
    {
        $invoices = Invoices::where('value_status', 3)->get();
        return view('invoices.invoices_parial_paid', compact('invoices'));
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
        $invoice_Attatchments = Invoice_attachments::where('invoices_ID', $request->invoice_id )->get();

        if($request->hasFile('pic'))
        {
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/'. $request->invoice_number), $imageName);
        }

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

    private function deleteInvoiceAttachments($id)
    {
        $invoice_Attatchments = Invoice_attachments::where('invoices_ID', $id )->get();

        if(!empty( $invoice_Attatchments))
        {
            foreach ($invoice_Attatchments as $invoice_Attatchment )
            Storage::disk('public_uploads')->delete($invoice_Attatchment->invoice_number . '/' . $invoice_Attatchment->file_name);
        }
    }

    public function export()
    {
        return \Excel::download(new InvoicesExport, 'Invoices.xlsx');
    }
}
