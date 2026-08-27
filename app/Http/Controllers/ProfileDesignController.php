<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileDesignController extends Controller
{
    public function index()
    {
        return view('desain-profil.index');
    }
}
