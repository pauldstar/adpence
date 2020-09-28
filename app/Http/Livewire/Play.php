<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Play extends Component
{
    public int $balance;

    public function mount()
    {
        $this->balance = optional(Auth::user())->balance ?? session('balance') ?? 0;
    }

    public function play()
    {
        $this->balance += 25;

        if (Auth::guest()) session(['balance' => $this->balance]);
        else Auth::user()->update(['balance' => $this->balance]);

        $this->emit(
            'balance-increment', str_pad($this->balance, 4, 0, STR_PAD_LEFT)
        );
    }

    public function render()
    {
        return view('livewire.play');
    }
}
