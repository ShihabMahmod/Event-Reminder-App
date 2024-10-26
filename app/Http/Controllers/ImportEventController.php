<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Event;
use Carbon\Carbon;

class ImportEventController extends Controller
{
    public function index()
    {
        return view('events.import');
    }
}
