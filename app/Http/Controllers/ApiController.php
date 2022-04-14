<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function welcome() {
        $collection = Http::get('https://www.swapi.tech/api/films')->collect();
        return view('welcome', ['collection' => $collection['result']]);
    }
}
