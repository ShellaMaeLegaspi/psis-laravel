<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FMISController extends Controller
{
    public function index()
    {
        return view('pages.access_denied', ['message' => 'FMIS module is not yet ported to Laravel.']);
    }
}
