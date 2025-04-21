<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('layout/head');
    }

    public function favorite(Request $request)
    {
        $userId = Auth::id();

        Favorite::updateOrCreate(
            ['user_id' => $userId, 'imdb_id' => $request->imdb_id],
            []
        );

        return response()->json(['status' => 'favorited']);
    }

    public function unfavorite(Request $request)
    {
        $userId = Auth::id();

        Favorite::where('user_id', $userId)
            ->where('imdb_id', $request->imdb_id)
            ->delete();

        return response()->json(['status' => 'unfavorited']);
    }

    public function getFavorites()
    {
        $userId = Auth::id();
        $favorites = Favorite::where('user_id', $userId)->pluck('imdb_id');

        return response()->json($favorites);
    }

    public function show($imdb_id)
    {
        return view('detail_movie', compact('imdb_id'));
    }

    public function favorite_list()
    {
        $client = new Client();
        $userId = auth()->id();
        $favorites =Favorite::where('user_id', $userId)->pluck('imdb_id');
        return view('favorite', compact('favorites'));
    }
}
