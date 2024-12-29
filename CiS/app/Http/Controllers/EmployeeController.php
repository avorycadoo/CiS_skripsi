<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $querybuilder = Employe::all(); // ini untuk pake model
        return view('employee.index', ['data' => $querybuilder]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new Employe();
        $data->name= $request->get('emp_name');
        $data->phone_number= $request->get('emp_phone');
        $data->address= $request->get('emp_address');
        $data->users_id= $request->get(key: 'user_id_emp');

        $data->save();
        // dd($data);

        return redirect()->route("employe.index")->with('status',"Horray, Your new category data is already inserted");
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
        $data = Employe::find($id);
        return view("employee.edit",compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employe $employe)
    {
        $updatedData = $employe;
        $updatedData->name = $request->name;
        $updatedData->phone_number = $request->phone_number;
        $updatedData->address = $request->address;

        $updatedData->save();
        

        return redirect()->route("employe.index")->with('status', "Horray, Your employee data is already updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employe $employe)
    {
        try {
            //if no contraint error, then delete data. Redirect to index after it.
            $deletedData = $employe;
            $deletedData->delete();
            return redirect()->route('employe.index')->with('status', 'Horray ! Your data is successfully deleted !');
        } 
        catch (\PDOException $ex) {
            // Failed to delete data, then show exception message
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect()->route('employe.index')->with('status', $msg);
        }
    }
}
