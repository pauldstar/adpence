<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Balance extends Component
{
    public $balance;

    public string $withdrawToken = '';

    protected $listeners = ['payment-received' => 'incrementBalance'];

    public function mount()
    {
        $this->balance = optional(Auth::user())->balance ?? session('balance', 0);
    }

    public function incrementBalance(int $value): void
    {
        $this->balance += $value;

        if (Auth::guest()) session(['balance' => $this->balance]);
        else Auth::user()->update(['balance' => $this->balance]);

        $this->emit('increment-balance');
    }

    public function setToken(): void
    {
        $this->withdrawToken = Str::uuid();

        if (Auth::guest()) {
        } else Auth::user()->update(['withdraw_token' => $this->withdrawToken]);
    }

    public function render()
    {
        return view('livewire.balance');
    }
}
