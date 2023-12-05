<?php

namespace App\Http\Controllers;

use App\Enums\Entry;
use App\Helper;
use App\Models\Employee;
use App\Models\HistoryEntry;
use App\Models\RequestUser;
use Carbon\Carbon;
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
        ])->latest()->first();

        $entry = Entry::IN;

        if(!is_null($employeeHistories)){
            $now = Carbon::now();
            $date = Carbon::parse($employeeHistories->date_at_in .' '.$employeeHistories->time_at_in);
            if($now->diffInHours($date) > 20){
                $this->createHistory($request, $employee);
            }elseif($employeeHistories->localisation_id == $request->localisation_id){
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

    private function validate_key($key, $arr){
        return array_key_exists($key, $arr) && !is_null($arr[$key]);
    }

    public function histories(Request $request){
        $validator = Validator::make($request->all(), [
            'start' => 'nullable|date',
            'end' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Bad request",
                'errors' => $validator->errors(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = [...$request->all()];

        if($this->validate_key('end', $data) && $this->validate_key('start', $data)){
            $histories = HistoryEntry::whereBetween('day_at_in',[Carbon::parse($data['start'])->format('Y-m-d'), Carbon::parse($data['end'])->format('Y-m-d')]);
        }elseif($this->validate_key('start', $data)){
            $date = Carbon::parse($data['start']);
            $histories = HistoryEntry::whereBetween('day_at_in',[$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
        }else{
            $now = Carbon::now();
            $histories = HistoryEntry::whereBetween('day_at_in',[$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')]);
        }
        $entriesByLocalisationByDay = Helper::getNombres($histories->get());

        return response()->json([
            'data' => $entriesByLocalisationByDay
        ]);
    }

    public function requestUpdateImage(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'matricule' => 'required|exists:employees,matricule'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Bad request",
                'errors' => $validator->errors(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }

        $employee = Employee::select('id')->where('matricule', $request->matricule)->first();

        $requestUser = new RequestUser();
        $requestUser->employee_id = $employee->id;
        $requestUser->image = $request->file('image')->store('photos', 'public');
        $requestUser->status = 'pending';
        $requestUser->save();

        return response()->json([], Response::HTTP_CREATED);
    }
}
