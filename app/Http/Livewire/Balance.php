<?php

namespace App\Http\Livewire;

use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Balance extends Component
{
    public int $balance;
    public string $creditToken;
    public ?string $sessionToken = null;

    protected $listeners = ['payment-received' => 'incrementBalance'];

    public function mount()
    {
        $this->setBalance();
        $this->setSessionToken();
    }

    public function hydrate()
    {
        $this->setBalance();
    }

    public function incrementBalance(int $value): void
    {
        $this->balance += $value;

        if (Auth::guest()) {
            Transaction::where('token', $this->sessionToken)->update([
                'amount' => $this->balance
            ]);
        } else Auth::user()->update(['balance' => $this->balance]);

        $this->emit('increment-balance');
    }

    public function getCreditToken(): void
    {
        if (Auth::guest()) {
            $this->creditToken = Transaction::findOrCreateCreditToken(
                $this->sessionToken
            );
        } else $this->creditToken = Auth::user()->creditToken;
    }

    private function setBalance()
    {
        if (Auth::guest()) {
            $this->balance = session()->has('sessionToken')
                ? Transaction::firstWhere('token', $this->sessionToken)->amount : 0;
        } else $this->balance = Auth::user()->balance;
    }

    private function setSessionToken()
    {
        if (Auth::guest()) {
            $this->sessionToken = session('sessionToken', fn() => Str::uuid());

            if (! session()->has('sessionToken')) {
                session(['sessionToken' =>$this->sessionToken]);
                Transaction::create(['token' => $this->sessionToken]);
            }
        }
    }

    public function render()
    {
        return view('livewire.balance');
    }
}
