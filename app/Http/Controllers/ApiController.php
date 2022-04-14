<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ApiController extends Controller
{
    public function welcome(): View {
        // Check in the cache if the data is still there, so that on refresh load times are faster
        $collection = Cache::get('movies');
        if (is_null($collection)) $collection = Http::get('https://www.swapi.tech/api/films')->collect();
        Cache::put('movies', $collection, now()->addMinutes(20));

        return view('welcome', ['collection' => $collection['result']]);
    }
}
