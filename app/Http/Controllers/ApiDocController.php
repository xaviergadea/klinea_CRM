<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocController extends Controller
{
    /**
     * Display the API documentation page.
     */
    public function index()
    {
        return view('api-docs.index');
    }
}
