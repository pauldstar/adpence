<?php

namespace App\Http\Controllers;

class PlayController extends Controller
{
    public function __invoke()
    {
        return view('play');
    }
}
