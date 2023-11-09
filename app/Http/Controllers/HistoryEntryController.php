<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HistoryEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class HistoryEntryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function in_out(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confidence' => 'required|numeric|min:0|max:100',
            'localisation_id' => 'required',
            'in_out' =>  'required|in:0,1',
            'matricule' =>   'required'
        ]);

        $employee =  Employee::where('matricule', $request->matricule)->first();

        $validator->after(function ($validator) use($request, $employee) {
            $localisationIds = array_map(fn($localisation) => $localisation['id'], config('localisation'));
            if(!in_array($request->localisation_id, $localisationIds)){
                $validator->errors()->add(
                    'localisation_id', 'localisation doesn\'t exist'
                );
            }

            if(is_null($employee)){
                $validator->errors()->add(
                    'matricule', 'matricule doesn\'t exist'
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => "Bad request",
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        HistoryEntry::create([
            'confidence' => $request->confidence,
            'localisation_id' => $request->localisation_id,
            'in_out' => $request->in_out,
            'employee_id' => $employee->id
        ]);

        return response()->json([
            'message' => "Created",
            'errors' => []
        ], Response::HTTP_CREATED);
    }
}
