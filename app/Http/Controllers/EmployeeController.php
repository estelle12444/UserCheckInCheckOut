<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class EmployeeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $employees = Employee::all();
            $employeeCount = $employees->count();
            return view('pages.employeesList', compact('employees', 'employeeCount'));
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
                'employee_id' => $employee->id,
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

    public function flexibilityIndex(Request $request)
    {
        if (!empty($request->selectedDates)) {
            $date_array = explode("to", $request->selectedDates);

            if (count($date_array) !== 2) {
                //throw new InvalidArgumentException("Invalid date format in selectedDates");
                return redirect()->back()->with('Invalid date format in selectedDates');
            }

            try {
                $startOfWeek = Carbon::parse(trim($date_array[0]));
                $endOfWeek = Carbon::parse(trim($date_array[1]));
            } catch (\Exception $e) {

                throw new InvalidArgumentException("Error parsing dates: " . $e->getMessage());
            }
        } else {
            try {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
            } catch (\Exception $e) {

                throw new RuntimeException("Error generating default dates: " . $e->getMessage());
            }
        }

        if (Auth::user()->role_id == 1) {
            $employees = Employee::all();
            $employeeCount = $employees->count();

            return view('pages.flexibilityIndex', compact('employees', 'employeeCount', 'startOfWeek', 'endOfWeek'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    public function absenceIndex(Request $request)
    {

        $date= '2023/11/27';
        $absence = DB::table('employees')
            ->whereNotExists(function ($query) use ($date) {
                $query->select(DB::raw(1))
                    ->from('history_entries')
                    ->whereRaw('history_entries.employee_id = employees.id')
                    ->where('history_entries.day_at_in', '=', $date);
            })
            ->get();

        $nbre=$absence->count();

        return view('pages.absenceIndex', compact('absence','date','nbre'));
    }
}
