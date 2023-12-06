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

use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\HistoryEntry;
use Illuminate\Support\MessageBag;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    const STRING_RULE = 'required|string|max:255';

    public function showRegistrationEmployeeExcel()
    {
        return view('auth.employeeExcelRegister');
    }


    public function registrationEmployeeForm()
    {
        return view('auth.employeeFormRegister');
    }

    public function importEmployee(Request $request)
    {
        $file = $request->file('file');
        if ($file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            if (in_array($extension, ['xls', 'xlsx', 'csv'])) {

                Excel::import(new EmployeesImport(), $file);
                return redirect()->route('employees.index')->with('success', 'Les employés ont été importés avec succès.');
            } else {
                return redirect()->back()->with('error', 'Le fichier n\'est pas un fichier Excel valide.');
            }
        } else {
            return redirect()->back()->with('error', 'Le fichier n\'est pas un fichier valide.');
        }
    }


    public function store(Request $request)
    {
        // Validation des données du formulaire
        $imagePath = $this->validateAnRefreshFacerecognition($request);
        if($this->save($request, $imagePath)){
            return redirect()->route('employees.index');
        }else{
            return redirect()->back()->withInput();
        }
    }




    public function updateEmployee(Request $request, $id)
    {
        $imagePath = $this->validateAnRefreshFacerecognition($request);
        if($this->save($request, $imagePath, $id)){
            return redirect()->route('employees.index');
        }else{
            return redirect()->back()->withInput();
        }
    }


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


    public function show($id, Request $request)
    {
        $employee = Employee::findOrFail($id);
        return view('pages.employeeShow', compact('employee', 'request'));
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

    public function dateRangeFromRequest($selectedDates)
    {
        if (!empty($selectedDates)) {
            $dateArray = explode("to", $selectedDates);

            if (count($dateArray) !== 2) {
                return redirect()->back()->with('Invalid date format in selectedDates');
            }

            try {
                $start = Carbon::parse(trim($dateArray[0]));
                $end = Carbon::parse(trim($dateArray[1]));
            } catch (\Exception $e) {
                throw new InvalidArgumentException("Error parsing dates: " . $e->getMessage());
            }
        } else {
            try {
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
            } catch (\Exception $e) {
                throw new RuntimeException("Error generating default dates: " . $e->getMessage());
            }
        }

        return compact('start', 'end');
    }

    public function flexibilityIndex(Request $request)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);

        if (Auth::user()->role_id == 1) {
            $employees = Employee::all();
            $employeeCount = $employees->count();

            return view('pages.flexibilityIndex', compact('employees', 'employeeCount', 'dateRange'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function absenceIndex(Request $request)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);
        $fromDate = $dateRange['start'];
        $toDate = $dateRange['end'];

        $employees = Employee::whereHas('historyEntries', function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('day_at_in', [$fromDate, $toDate]);
        })->get();

        foreach ($employees as $employee) {
            $absentDays = [];
            $currentDate = Carbon::parse($fromDate);

            while ($currentDate->lte($toDate)) {
                if ($employee->historyEntries->where('day_at_in', $currentDate->format('Y-m-d'))->count() === 0) {
                    $absentDays[] = $currentDate->format('Y-m-d');
                }
                $currentDate->addDay();
            }

            $employee->absentDays = $absentDays;
        }



        $nbre = count($employees);

        return view('pages.absenceIndex', compact('absence', 'dateRange', 'nbre'));
    }

    private function save($request, $imagePath, $id=null){
        $save = true;
        $employee = is_null($id) ? new Employee() : Employee::findOrFail($id);
        $employee->matricule = $request->input('matricule');
        $employee->name = $request->input('name');
        $employee->designation = $request->input('designation');
        $employee->department_id = $request->input('department_id');

        if ($imagePath != "") {
            $employee->image_path = $imagePath;
        }

        try{
            $employee->save();
        }catch(\Throwable $e){
            $save = false;
            $request->session()->flash('error', "Quicklook already used by another employee");
        }

        return $save;
    }

    private function validateAnRefreshFacerecognition(Request $request){
        $validator = Validator::make([
            'matricule' => self::STRING_RULE,
            'name' => self::STRING_RULE,
            'designation' => self::STRING_RULE,
            'department_id' => 'required|int',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ]);

        $imagePath = "";

        $validator->after(function($validator) use($request, &$imagePath){
            if ($request->hasFile('image')) {
                $fileName = $request->input('matricule').', '.$request->input('name').', '.$request->input('designation');
                $fileName .= ', '.\App\Helper::searchByNameAndId('department', $request->input('department_id'))->name;
                $guessExtension = $request->file('image')->guessExtension();
                $imagePath = $request->file('image')->storeAs('photos', $fileName.'.'.$guessExtension,'public');

                $response = Http::withHeaders(['Accept' => 'multipart/form-data'])
                    ->attach('file', file_get_contents('storage/'.$imagePath), $fileName.'.'.$guessExtension)
                    ->post(env('FACERECOGNITION_BASE_URI').'/upload', []);

                if($response->status() == 200){
                    $restartResponse = Http::get(env('FACERECOGNITION_BASE_URI').'/restart');
                    foreach ($restartResponse->json()['failed info'] as $value) {
                        if($value[0] == $request->input('matricule')){
                            $validator->errors()->add(
                                'image', 'Provide an image where a face can be detected'
                            );
                            $this->removeImage($imagePath);
                            break;
                        }
                    }
                }else{
                    $validator->errors()->add(
                        'image', 'Check the face recognition server\'s it may be down'
                    );
                    $this->removeImage($imagePath);
                }
            }
        });

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return $imagePath;
    }

    private function removeImage($path){
        if(Storage::disk('public')->exists($path)){
            Storage::disk('public')->delete($path);
        }
    }
}
