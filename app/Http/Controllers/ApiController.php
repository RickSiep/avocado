<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{
    public function welcome(): View {
        // Check in the cache if the data is still there, so that on refresh load times are faster
        $collection = Cache::get('movies');
        if (is_null($collection)) $collection = Http::get('https://www.swapi.tech/api/films')->collect();
        Cache::put('movies', $collection, now()->addMinutes(20));

        return view('welcome', ['collection' => $collection['result']]);
    }

    public function movie($id) {
        // Same cache trick to make this app faster and not keep making requests
        $collection = Cache::get("movie/$id");
        if (is_null($collection)) $collection = Http::get("https://www.swapi.tech/api/films/$id")->collect();

        // Throw a 404 if the movie doesn't find anything
        if ($collection->count() <= 1) abort(404);
        Cache::put("movie/$id", $collection, now()->addMinutes(20));

        return view('movie', ['movie' => $collection['result']]);
    }

    public function favorite($id, Favorite $favorite) {
        // Fill in the database and save
        $favorite->fill([
            'movie_id' => $id,
            'favorite' => 1
        ]);

        $favorite->save();

        return back();
    }
}
