<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $querybuilder = Warehouse::all(); // ini untuk pake model
        return view('warehouse.index', ['data' => $querybuilder]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new Warehouse();
        $data->name = $request->get('warehouse_name');
        $data->address = $request->get('warehouse_address');

        $data->save();
        // dd($data);

        return redirect()->route("warehouse.index")->with('status', "Horray, Your new warehouse data is already inserted");
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
        $data = Warehouse::find($id);

        return view("warehouse.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Temukan data berdasarkan ID
         $updatedData = Warehouse::find($id);

         // Pastikan data ditemukan sebelum melakukan update
         if ($updatedData) {
             $updatedData->name = $request->name;
             $updatedData->address = $request->address;
             $updatedData->save();
 
             return redirect()->route("warehouse.index")->with('status', "Horray, Your warehouse data is already updated");
         } else {
             return redirect()->route("warehouse.index")->with('status', "Failed to update data, warehouse not found.");
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Temukan data berdasarkan ID
            $deletedData = Warehouse::find($id);

            // Pastikan data ditemukan sebelum melakukan delete
            if ($deletedData) {
                $deletedData->delete();
                return redirect()->route('warehouse.index')->with('status', 'Horray! Your data is successfully deleted!');
            } else {
                return redirect()->route('warehouse.index')->with('status', 'Failed to delete data, warehouse not found.');
            }
        } catch (\PDOException $ex) {
            // Jika ada masalah pada penghapusan data
            $msg = "Failed to delete data! Make sure there is no related data before deleting it.";
            return redirect()->route('warehouse.index')->with('status', $msg);
        }
    }
}
