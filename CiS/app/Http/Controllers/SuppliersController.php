<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $querybuilder = Suppliers::all(); // ini untuk pake model
        return view('suppliers.index', ['data' => $querybuilder]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new Suppliers();
        $data->company_name = $request->get('sup_name');
        $data->phone_number = $request->get('sup_phone');
        $data->email = $request->get('sup_email');
        $data->address = $request->get('sup_address');

        $data->save();
        // dd($data);

        return redirect()->route("suppliers.index")->with('status', "Horray, Your new suppliers data is already inserted");
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
        $data = Suppliers::find($id);
        return view("suppliers.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $suppliers = Suppliers::findOrFail($id); // Mengambil data berdasarkan ID
        $suppliers->company_name = $request->input('company_name'); // Pastikan nama input sesuai
        $suppliers->phone_number = $request->input('phone_number');
        $suppliers->email = $request->input('email');
        $suppliers->address = $request->input('address');

        $suppliers->save();

        return redirect()->route("suppliers.index")->with('status', "Horray, Your supplier data is already updated");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $suppliers = Suppliers::findOrFail($id); // Mengambil data berdasarkan ID
            $suppliers->delete();
            return redirect()->route('suppliers.index')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect()->route('suppliers.index')->with('status', $msg);
        }
    }

}
