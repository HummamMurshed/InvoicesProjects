<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    //Private Functons
    private function  toThisPage()
    {
        return '/sections';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $setions = Sections::all();
        return view('sections.sections')->with("sections", $setions);
    }
    private function isSectionFound($name)
    {
        return sections::where("section_name","=", $name)->exists();
    }
    private function  validateBeforDo(Request $request)
    {
        $validate = $request->validate([
            "section_name" => "required|unique:sections|max:255",
            "description" => "required",

        ],[
            "section_name.required" => 'يرجى إدحال اسم القسم',
            "section_name.unique" => ' هذاالقسم موجود بالفعل',
            "section_name.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',
            "description.required" => 'يرجى إدحال وصف القسم',
            "description.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',


        ]);
    }
    private function saveMeassgToSession($type, $message)
    {
        session()->flash($type,$message);

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

        $this->validateBeforDo($request);


            sections::create([
                "section_name" => $request->section_name,
                "description"  => $request->description,
                "created_by"  => Auth::user()->name,

            ]);
            $this->saveMeassgToSession('Add', 'تم إضافة القسم بنجاح');
            return redirect($this->toThisPage());





    }

    /**
     * Display the specified resource.
     */
    public function show(Sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        //$this->validateBeforDo($request);
        $this->validate = $request->validate([
                'section_name' => 'required|max:255|unique:sections,section_name,'.$request->id,
                'description' => 'required',
        ],[
            "section_name.required" => 'يرجى إدحال اسم القسم',
            "section_name.unique" => ' هذاالقسم موجود بالفعل',
            "section_name.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',
            "description.required" => 'يرجى إدحال وصف القسم',
            "description.max" => 'يرجى إدحال عدد أحرف أقل من 255 حرف ',


        ]);

        $section = Sections::find($request->id);
        $section->update([
            "section_name" => $request->section_name,
            "description" => $request->description,

        ]);
        $this->saveMeassgToSession('update', 'تم تعديل القسم بنجاح');
        return redirect($this->toThisPage());


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sections $sections)
    {
        //
    }
}
