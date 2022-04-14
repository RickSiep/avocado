<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{
    /**
     * Returns
     * @return View
     */
    public function welcome(Favorite $favorite): View {
        // Check in the cache if the data is still there, so that on refresh load times are faster
        $collection = Cache::get('movies');
        if (is_null($collection)) $collection = Http::get('https://www.swapi.tech/api/films')->collect();
        Cache::put('movies', $collection, now()->addMinutes(20));

        return view('welcome', ['collection' => $collection['result'], 'favorite' => $favorite]);
    }

    public function show_movie($id): View {
        // Same cache trick to make this app faster and not keep making requests
        $collection = Cache::get("movie/$id");
        if (is_null($collection)) $collection = Http::get("https://www.swapi.tech/api/films/$id")->collect();

        // Throw a 404 if the movie doesn't find anything
        if ($collection->count() <= 1) abort(404);
        Cache::put("movie/$id", $collection, now()->addMinutes(20));

        return view('movie', ['movie' => $collection['result']]);
    }

    public function save_favorite($id, Favorite $favorite): RedirectResponse {
        // Fill in the database and save
        $favorite->fill([
            'movie_id' => $id,
            'favorite' => 1
        ]);

        $favorite->save();

        return back();
    }

    public function delete_favorite($id): RedirectResponse {
        // Get the matching record and delete it
        $favorite = Favorite::where('movie_id', $id)->first();

        $favorite->delete();

        return back();
    }
}
