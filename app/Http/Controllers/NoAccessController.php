<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoAccessController extends Controller
{
    public function index()
    {
        return view('admin.no-access');
    }
}
