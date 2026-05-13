<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        return view('pages.access_denied', ['message' => 'Branch module is not yet ported to Laravel.']);
    }
}
