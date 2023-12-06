<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentLayout;

class HomeController extends Controller
{
    public function index()
    {
        return view('content.home');
    }

    public function dashboardIndex()
    {
        return view('admin.dashboard');
    }

}
