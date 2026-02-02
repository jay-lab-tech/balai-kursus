<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index()
    {
        return view('instruktur.dashboard.index');
    }
}
