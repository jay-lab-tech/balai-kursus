<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('instruktur::instruktur.dashboard.index');
    }
}
