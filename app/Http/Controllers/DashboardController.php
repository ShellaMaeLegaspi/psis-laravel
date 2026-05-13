<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Temporarily disable database calls until SQL Server is configured
        return view('pages.index', [
            'totalICS' => 0,
            'totalPPMP' => 0,
            'db_error' => 'Database temporarily disabled - SQL Server needs to be configured'
        ]);
    }
}
