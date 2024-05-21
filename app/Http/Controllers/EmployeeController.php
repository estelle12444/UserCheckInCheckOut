<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use Illuminate\Support\Facades\URL;

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
        $response = $this->validateAnRefreshFacerecognition($request);
        if ($response->status() === 200) {
            [$bool, $message, $employee_id] = $this->save($request, $response->getContent());
            $url = URL::temporarySignedRoute('refresh_model', now()->addMinute(), ['employee' => $employee_id]);
            return $bool ? response()->json(array_merge($message, ['url' => $url])) : response()->json($message, 400);
        }
        return $response;
    }


    public function updateEmployee(Request $request, $id)
    {
        $response = $this->validateAnRefreshFacerecognition($request);
        if ($response->status() === 200) {
            [$bool, $message, ] = $this->save($request, $response->getContent(), $id);
            $url = URL::temporarySignedRoute('refresh_model', now()->addMinute(), ['employee' => $id]);
            return $bool ? response()->json(array_merge($message, ['url' => $url])) : response()->json($message, 400);
        }
        return $response;
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
        } elseif (!$employee->activated) {

            $employee->activated = true;
            $employee->save();
            return redirect()->back()->with('success', 'Employé activé avec succès.');
        } else {
            return redirect()->back()->with('error', 'L\'employé est déjà actif.');
        }
    }

    public function deleteUser(Employee $employee)
    {
        $existingUser = User::where('employee_id', $employee->id)->first();

        if ($existingUser) {
            try {
                $existingUser->delete();
                $employee->user_id = null;
                $employee->activated = false;
                $employee->save();

                return redirect()->back()->with('success', 'Utilisateur associé à l\'employé supprimé avec succès.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Échec de la suppression de l\'utilisateur associé à l\'employé : ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Aucun utilisateur associé à l\'employé n\'a été trouvé.');
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

        return view('pages.absenceIndex', compact('employees', 'dateRange', 'fromDate', 'nbre', 'toDate'));
    }

    public function restartModelRecognition(Request $request, Employee $employee) {
        abort_if(! $request->hasValidSignature(), 401, "Not authorized");

        try {
            $restartResponse = Http::get(env('FACERECOGNITION_BASE_URI') . '/restart');

            foreach ($restartResponse->json()['failed info'] as $value) {
                if ($value[0] == $employee->matricule) {
                    $this->removeImage($employee->image_path);
                    return response()->json(['image' => 'Provide an image where a face can be detected']);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['image' => 'Check the face recognition server\'s it may be down']);
        }

        session()->flash('success', 'face recognition server\'s successfully updated');

        return response()->json([
            'url' => route('employees.index')
        ]);
    }

    private function save($request, $imagePath, ?int $id = null)
    {
        $save = true;
        $message = [
            'success' => !!$id ? "Employé a bien été modifié" : "Employé a bien été enregistré",
        ];

        $employee = is_null($id) ? new Employee() : Employee::findOrFail($id);
        $employee->matricule = $request->input('matricule');
        $employee->name = $request->input('name');
        $employee->designation = $request->input('designation');
        $employee->department_id = $request->input('department_id');

        if ($imagePath != "") {
            $employee->image_path = $imagePath;
        }

        try {
            $employee->save();
        } catch (\Throwable $e) {
            $save = false;
            $message = [
                'matricule' => "Quicklook already used by another employee"
            ];
        }

        return [$save, $message, $employee->id];
    }

    private function validateAnRefreshFacerecognition(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $validator->after(function ($validator) use ($request, &$imagePath) {
            $fileName = $request->input('matricule') . ', ' . $request->input('name') . ', ' . $request->input('designation');
            $fileName .= ', ' . \App\Helper::searchByNameAndId('department', $request->input('department_id'))->name;

            if ($request->hasFile('image')) {
                $guessExtension = $request->file('image')->guessExtension();
                $imagePath = $request->file('image')->storeAs('photos', $fileName . '.' . $guessExtension, 'public');
            }else{
                $imagePath = Employee::select('image_path')->where('matricule', $request->matricule)->first()->image_path;
            }

            if(isset($imagePath) && $imagePath != ""){
                $path  = explode('/', $imagePath);
                $extension = explode('.', $path[1]);
                try {
                    $response = Http::withHeaders(['Accept' => 'multipart/form-data'])
                        ->attach('file', file_get_contents('storage/' . $imagePath), $request->hasFile('image') ? $path[1] : $fileName.".".$extension[1] )
                        ->post(env('FACERECOGNITION_BASE_URI') . '/upload', []);

                    if ($response->status() != 200) {
                        $validator->errors()->add(
                            'image',
                            'Check the face recognition server\'s it may be down'
                        );
                        $this->removeImage($imagePath);
                    }
                } catch (\Throwable $th) {
                    $validator->errors()->add(
                        'image',
                        'Check the face recognition server\'s it may be down'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                400
            );
        }

        return response()->json($imagePath);
    }

    private function removeImage($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
