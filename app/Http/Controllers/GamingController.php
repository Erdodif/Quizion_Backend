<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GamingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(["user_id", "quiz_id"]);
        $game = new Game();
        $game->fill($data);
        $game->save();
        return redirect(url("quiz/" . $game->quiz_id));
    }
}
