<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $querybuilder = Companies::all(); // ini untuk pake model
        return view('companies.index', ['data' => $querybuilder]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new Companies();
        $data->name= $request->get('comp_name');
        $data->phone_number= $request->get('comp_phone');
        $data->address= $request->get('comp_address');
        $data->email= $request->get('comp_email');
        $data->logo= $request->get('comp_logo');
        $data->phone_number= $request->get('comp_phone');
        $data->phone_number= $request->get('comp_phone');
        
        $data->save();
        // dd($data);

        return redirect()->route("customer.index")->with('status',"Horray, Your new companies data is already inserted");
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
