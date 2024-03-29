<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:المنتجات',['only' => ['index']]);
        $this->middleware('permission:اضافة منتج',['only'=>['create','store']]);
        $this->middleware('permission:تعديل منتج',['only' => ['edit','update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }
    private function validProduct(Request $request)
    {
        $validate = $request->validate([
            "name" => "required:products|max:255",
            "section_ID" => "required"
        ],[
            "name.required"  => 'يرجى إدحال اسم القسم',
            "name.unique" =>  ' هذاالقسم موجود بالفعل',
            "name.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',
            "section_ID.required" => 'يرجى إدحال اسم القسم',
            ]
        );
    }
    private function  toThisPage()
    {
        return '/products';
    }
    private function saveMeassgToSession($type, $message)
    {
        session()->flash($type,$message);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $allProducts = Products::all();
        $Sections = Sections::all();
        return view('products.products', compact('allProducts'))->with("sections", $Sections);
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
        $this->validProduct($request);
        $r = Products::create([
            "name" => $request->name,
             "description" => $request->description,
             "section_ID" => $request->section_ID,
        ]);
        $this->saveMeassgToSession('success', 'تم إضافة المنتج بنجاح');
        return redirect($this->toThisPage());
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $this->validate = $request->validate([
            'name' => 'required|max:255|unique:products,name,'.$request->pro_id,
            'description' => 'nullable',
        ],[
            "name.required" => 'يرجى إدحال اسم القسم',
            "name.unique" => ' هذاالقسم موجود بالفعل',
            "name.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',
            "description.required" => 'يرجى إدحال وصف القسم',
            "description.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',


        ]);

        $id = Sections::where("section_name",$request->section_name)->first()->id;
        $product = Products::findOrFail($request->pro_id);
        $product->update([
            "name" => $request->name,
            "section_ID" => $id,
            "description" => $request->description
        ]);
        $this->saveMeassgToSession('success', 'تم تعديل المنتج بنجاح');
        return redirect($this->toThisPage());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $product = Products::findOrFail($request->pro_id );
        $product->delete();
        $this->saveMeassgToSession('success', 'تم حذف المنتج بنجاح');
        return redirect($this->toThisPage());

    }
}
