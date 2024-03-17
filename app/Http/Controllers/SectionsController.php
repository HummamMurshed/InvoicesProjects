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

        if($this->isSectionFound($request->input('section_name')))
        {
            session()->flash('Error','خطأ القسم مسجل مسبقاً');
            return redirect($this->toThisPage());
        }
        else
        {
            sections::create([
                "section_name" => $request->section_name,
                "description"  => $request->description,
                "created_by"  => Auth::user()->name,

            ]);
            session()->flash('Add','تم إضافة القسم بنجاح');
            return redirect($this->toThisPage());
        }




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
    public function update(Request $request, Sections $sections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sections $sections)
    {
        //
    }
}
