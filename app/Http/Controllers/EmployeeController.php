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
        // Logique pour afficher un employé spécifique
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
         

            // Associez l'utilisateur à l'employé
            $employee->user_id = $user->id;
            $employee->activated = true;
            $employee->save();

            // La redirection doit être en dehors de la condition
            return redirect()->back()->with('success', 'Employé activé avec succès.');
        } else {
            // L'utilisateur existe déjà, gestion appropriée (par exemple, ne rien faire ou envoyer une notification)
            return redirect()->back()->with('error', 'L\'employé est juste inactif.');
        }

    }

    public function deactivateEmployee(Employee $employee)
    {
        // Désactiver l'employé en supprimant l'utilisateur associé
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->user_id = null;
        $employee->activated = false;
        $employee->save();

        return redirect()->back()->with('success', 'Employé désactivé avec succès.');
    }
}
