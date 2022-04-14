<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ApiController extends Controller
{
    /**
     * Collects the data from the api and then caches it, or get the data from the cache
     * Then return it with the view
     * @return View
     */
    public function welcome(Favorite $favorite): View {
        // Check in the cache if the data is still there, so that on refresh load times are faster
        $collection = Cache::get('movies');
        if (is_null($collection)) $collection = Http::get('https://www.swapi.tech/api/films')->collect();
        Cache::put('movies', $collection, now()->addMinutes(20));

        return view('welcome', ['collection' => $collection['result'], 'favorite' => $favorite]);
    }

    /**
     * Get the specific movie from the api by the movie, same cache trick
     * @param $id
     * @return View
     */
    public function show_movie($id): View {
        // Same cache trick to make this app faster and not keep making requests
        $collection = Cache::get("movie/$id");
        if (is_null($collection)) $collection = Http::get("https://www.swapi.tech/api/films/$id")->collect();

        // Throw a 404 if the movie doesn't find anything
        if ($collection->count() <= 1) abort(404);
        Cache::put("movie/$id", $collection, now()->addMinutes(20));

        return view('movie', ['movie' => $collection['result']]);
    }

    /**
     * Save a favorite movie
     * @param $id
     * @param Favorite $favorite
     * @return RedirectResponse
     */
    public function save_favorite($id, Favorite $favorite): RedirectResponse {
        // Fill in the database and save
        $favorite->fill([
            'movie_id' => $id,
            'favorite' => 1
        ]);

        $favorite->save();

        return back();
    }

    /**
     * Delete a favorite
     * @param $id
     * @return RedirectResponse
     */
    public function delete_favorite($id): RedirectResponse {
        // Get the matching record and delete it
        $favorite = Favorite::where('movie_id', $id)->first();

        $favorite->delete();

        return back();
    }
}
