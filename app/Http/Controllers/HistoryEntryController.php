<?php

namespace App\Http\Controllers;

use App\Enums\Entry;
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
                'errors' => $validator->errors(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }

        $employeeHistories = HistoryEntry::where([
            'employee_id' => $employee->id,
            'day_at_out' => null
        ])->first();

        $entry = Entry::IN;

        if(!is_null($employeeHistories)){
            if($employeeHistories->localisation_id == $request->localisation_id){
                $this->updateHistory($employeeHistories, $request);
                $entry = Entry::OUT;
            }else{
                $this->updateHistory($employeeHistories, $request);
                $this->createHistory($request, $employee);
            }
        }else{
            $this->createHistory($request, $employee);
        }

        $operation = $entry ? 'rentrer' : 'sortir';
        $site = config('localisation')[$request->localisation_id - 1]["name"];

        return response()->json([
            'message' => $employee->name." vous venez de ".$operation." sur le site ". $site." à ".now(),
            'data' => [
                'name' => $employee->name,
                'localisation' => config('localisation')[$request->localisation_id - 1]["name"],
                'operation' => $entry ? 'Entrer' : 'Sortir',
                'time' => now()
            ],
            'errors' => [],
            'success' => true
        ], Response::HTTP_CREATED);
    }

    private function updateHistory(HistoryEntry $history , Request $request){
        $history->out_confidence = $request->confidence;
        $history->day_at_out = now()->format('Y-m-d');
        $history->time_at_out = now()->format('H:i:s');
        $history->save();
    }

    private function createHistory(Request $request, Employee $employee){
        HistoryEntry::create([
            'in_confidence' => $request->confidence,
            'localisation_id' => $request->localisation_id,
            'employee_id' => $employee->id,
            'day_at_in' => now()->format('Y-m-d'),
            'time_at_in' => now()->format('H:i:s'),
        ]);
    }
}
