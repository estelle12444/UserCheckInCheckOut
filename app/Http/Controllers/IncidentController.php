<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index()
    {

        return view('pages.incidentIndex');
    }
}
