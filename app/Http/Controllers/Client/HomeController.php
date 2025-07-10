<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $query = request('query');
        $movies = \App\Models\Movie::query()
            ->with('genres');

        if ($query) {
            $movies = $movies->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhereHas('genres', function($g) use ($query) {
                      $g->where('name', 'like', '%' . $query . '%');
                  });
            });
        }

        $movies = $movies->orderBy('release_date', 'desc')->take(8)->get();
        
        // Lấy phim mới phát hành
        $newReleases = \App\Models\Movie::query()
            ->with('genres')
            ->orderBy('release_date', 'desc')
            ->take(6)
            ->get();

        return view('client.home', compact('movies', 'newReleases', 'query'));
    }
}