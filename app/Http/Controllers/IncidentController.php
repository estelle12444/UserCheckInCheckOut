<?php

namespace App\Http\Controllers;

use App\Models\RequestUser;
use Illuminate\Http\Request;

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
        $incident->update(['status' => 'accept']);
        return redirect()->route('incidents.listAccept')->with('success', 'Incident accepté avec succès.');
    }

    public function reject(Request $request, RequestUser $incident)
    {
        $incident->update(['status' => 'reject']);
        return redirect()->route('incidents.listReject')->with('success', 'Incident refusé avec succès.');
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
