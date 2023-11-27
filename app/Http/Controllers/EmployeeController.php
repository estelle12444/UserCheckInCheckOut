<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $employees = Employee::all();
            $employeeCount = $employees->count();
            return view('pages.employeesList', compact('employees','employeeCount'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    public function show($id)
    {

        $employee = Employee::findOrFail($id);
        return view('pages.employeeShow', compact('employee'));
    }


    public function activateEmployee(Employee $employee)
    {
        $existingUser = User::where('employee_id', $employee->id)->first();

        if (!$existingUser) {
            $user = User::create([
                'name' => $employee->name,
                'employee_id'=> $employee->id,
                'email' => $employee->matricule . '@example.com',
                'role_id' => 1,
                'password' => bcrypt('password'),
            ]);
            $employee->user_id = $user->id;
            $employee->activated = true;
            $employee->save();
            return redirect()->back()->with('success', 'Employé activé avec succès.');
        } else {

            return redirect()->back()->with('error', 'L\'employé est juste inactif.');
        }

    }

    public function deactivateEmployee(Employee $employee)
    {

        if ($employee->user) {
            dd('Before user deletion', $employee->user);

            if ($employee->user->delete()) {
                // La suppression a réussi
                $employee->user_id = null;
                $employee->activated = false;
                $employee->save();

                return redirect()->back()->with('success', 'Employé désactivé avec succès.');
            } else {
                // La suppression a échoué
                return redirect()->back()->with('error', 'Échec de la désactivation de l\'employé.');
            }
        } else {
            // Aucun utilisateur associé
            $employee->user_id = null;
            $employee->activated = false;
            $employee->save();

            return redirect()->back()->with('success', 'Employé désactivé avec succès.');
        }

    }

    public function flexibilityIndex()
    {
        if (Auth::user()->role_id == 1) {
            $employees = Employee::all();
            $employeeCount = $employees->count();

            return view('pages.flexibilityIndex', compact('employees','employeeCount'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
