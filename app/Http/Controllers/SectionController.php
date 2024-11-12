<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $sections = Section::all();
        return view('sections.index', compact('sections'));
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
    public function store(StoreSectionRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = Auth::user()->name;

        Section::create($data);

        return to_route('sections.index')->with('success', 'تم إضافة القسم بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return to_route('sections.index')->with('success', 'تم تعديل القسم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'تم حذف القسم بنجاح.');
    }

    public function getProducts($id)
    {
        // Optional debugging
        // Log::info("Fetching products for section ID: " . $id);

        $products = Product::where('section_id', $id)->pluck('name', 'id');
        return response()->json($products);
    }

}
