<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametersController extends Controller
{
    public function index()
    {
        return view('pages.access_denied', ['message' => 'Parameters module is not yet ported to Laravel.']);
    }
}
