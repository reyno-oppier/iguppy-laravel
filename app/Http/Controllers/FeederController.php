<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeederController extends Controller
{
    public function index()
    {
        return view('feeder'); // make sure the blade file is resources/views/feeder.blade.php
    }
}
