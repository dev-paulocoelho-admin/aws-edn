<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('home.index');
    }
}
