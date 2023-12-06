<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\RequestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IncidentController extends Controller
{
    public function index(RequestUser $incident)
    {
        $incidents = RequestUser::with('employee')
            ->where('status', 'pending')
            ->get();
        return view('pages.incidentIndex', compact('incidents'));
    }

    public function countPendingIncidents()
    {
        $nbre_incidents = RequestUser::where('status', 'pending')->count();
        return $nbre_incidents;
    }

    public function latestPendingIncidents()
    {
        $latestIncidents = RequestUser::with('employee')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return $latestIncidents;
    }


    public function accept(Request $request, RequestUser $incident)
    {
        $imageInfo = explode('/', $incident->image);
        $response = Http::withHeaders(['Accept' => 'multipart/form-data'])
            ->attach('file', file_get_contents('storage/' . $incident->image), $imageInfo[1])
            ->post(env('FACERECOGNITION_BASE_URI') . '/upload', []);

        if ($response->status() == 200) {
            $restartResponse = Http::get(env('FACERECOGNITION_BASE_URI') . '/restart');
            foreach ($restartResponse->json()['failed info'] as $value) {
                if ($value[0] == $incident->employee->matricule) {
                    $incident->status = 'to_delete';
                    $incident->save();
                    return redirect()->route('incidents.to_delete')->with('error', 'Provide an image where a face can be detected');
                }
            }
        } else {
            return redirect()->back()->with('error', 'Check the face recognition server\'s it may be down');
        }

        $incident->status = 'accept';
        $incident->save();

        $incident->employee->image_path = $incident->image;
        $incident->employee->save();

        return redirect()->back()->with('success', 'Incident accepté avec succès.');
    }

    public function reject(Request $request, RequestUser $incident)
    {
        $incident->update(['status' => 'reject']);
        return redirect()->back()->with('success', 'Incident refusé avec succès.');
    }


    public function listToDelete(Request $request, RequestUser $incident)
    {
        $listDelete = RequestUser::with('employee')
            ->where('status', 'to_delete')
            ->get();
        return view('pages.incidentDelete', compact('listDelete'));
    }

    public function destroy(RequestUser $incident)
    {
        $incidentId = $incident->id;
        $incident->delete();

        return redirect()->back()->with('success', "Incident #$incidentId supprimé avec succès.");
    }


    public function listReject(Request $request, RequestUser $incident)
    {
        $listRejects = RequestUser::with('employee')
            ->where('status', 'reject')
            ->get();
        return view('pages.incidentReject', compact('listRejects'));
    }

    public function listAccept(Request $request, RequestUser $incident)
    {
        $listAccepts =  RequestUser::with('employee')
            ->where('status', 'accept')
            ->get();
        return view('pages.incidentAccept', compact('listAccepts'));
    }
}
