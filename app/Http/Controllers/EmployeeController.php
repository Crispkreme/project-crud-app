<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facade\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{

    public function fetchEmployee(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');

        // Get the currently logged-in user (admin)
        $adminId = auth()->user()->id;

        $query = Employee::query();

        // Only fetch employees with the current admin's referral_id
        $query->where('referral_id', $adminId);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('position', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('age', 'like', '%' . $search . '%');
            });
        }

        // Exclude any records of the admin
        $query->whereDoesntHave('user', function ($q) {
            $q->where('role', 'admin');
        });

        $employees = $query->skip(($page - 1) * $perPage)
                            ->take($perPage)
                            ->get();

        $total = $query->count();

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
    public function storeEmployee(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'position' => 'required|max:50',
            'address' => 'string|nullable',
            'age' => 'required|numeric',
        ]);

        if ($request->id) {
            $employee = Employee::find($request->id);
            $userId = $employee->user_id;
            $user = User::find($userId);
        } else {
            $i = User::count(); 
            $email = 'employee' . ($i + 1) . '@employee.com';
            $role = 'employee'; 
            Log::info('Creating user with email: ' . $email . ' and role: ' . $role);
            $user = User::create([
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => $role,
                'remember_token' => Str::random(10),
            ]);
        }

        $employee = Employee::updateOrCreate(
            ['id' => $request->id],
            [
                'user_id' => $user->id,
                'referral_id' => auth()->user()->id,
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'age' => $validatedData['age'],
                'position' => $validatedData['position'],
            ]
        );

        return response()->json([
            'message' => 'Successfully saved',
            'employee' => $employee
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editEmployee($id)
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
    public function deleteEmployee($id)
    {
        $employees = Employee::findOrFail($id);
        $employees->delete();

        return response()->json([
            'message' => 'Successfully Deleted',
            'employee' => $employees
        ], 200);
    }
}
