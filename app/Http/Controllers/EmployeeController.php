<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

use Illuminate\Support\Facade\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    public function fetchEmployee(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $employees = Employee::skip(($page - 1) * $perPage)
                            ->take($perPage)
                            ->get();

        $total = Employee::count();

        return response()->json([
            'employees' => $employees,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'position' => 'required|max:50',
            'address' => 'string|nullable',
            'age' => 'required|numeric',
        ]);

        $employee = Employee::updateOrCreate(
            ['id' => $request->id],
            $validatedData
        );

        return response()->json([
            'message' => 'Successfully saved',
            'employee' => $employee
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employees = Employee::findOrFail($id);

        return response()->json([
            'message' => 'Successfully updated',
            'employee' => $employees
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $employees = Employee::findOrFail($id);
        $employees->delete();

        return response()->json([
            'message' => 'Successfully Deleted',
            'employee' => $employees
        ], 200);
    }
}
