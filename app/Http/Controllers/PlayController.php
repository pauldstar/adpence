<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PlayController extends Controller
{
    public function __invoke()
    {
        $balance = $this->getBalance();
        return view('play', compact('balance'));
    }

    private function getBalance()
    {
        $balance = optional(Auth::user())->balance ?? session('balance') ?? 0;
        return str_pad($balance, 4, 0, STR_PAD_LEFT);
    }
}
