<?php

namespace App\Http\Livewire;

use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class Balance extends Component
{
    public int $balance;
    public string $creditToken;
    public ?string $sessionUuid = null;

    protected array $listeners = ['increment-balance' => 'incrementBalance'];

    public function mount()
    {
        $this->setBalance();
    }

    public function hydrate()
    {
        $this->setBalance();
    }

    public function incrementBalance(int $amount): void
    {
        if ($amount <= 0) return;

        $this->balance += $amount;

        if (Auth::guest()) {
            $this->setSessionUuid();

            Transaction::whereUuid($this->sessionUuid)->update([
                'amount' => $this->balance
            ]);
        } else Auth::user()->update(['balance' => $this->balance]);

        $this->emit('payment-received', $amount);
    }

    public function getCreditToken(): void
    {
        if (Auth::guest()) {
            if ($uuid = $this->getSessionUuid()) {
                $this->creditToken = Transaction::findOrCreateCreditToken($uuid);
            } else $this->creditToken = 'No credit, no token';
        } else $this->creditToken = Auth::user()->creditToken;
    }

    private function setBalance()
    {
        if (Auth::guest()) {
            $uuid = $this->getSessionUuid();
            $this->balance = $uuid ? Transaction::firstWhere('uuid', $uuid)->amount : 0;
        } else $this->balance = Auth::user()->balance;
    }

    private function getSessionUuid(bool $mostRecent = false)
    {
        return Session::get('sessionUuid', $this->sessionUuid);
    }

    private function setSessionUuid()
    {
        if ($this->sessionUuid) {
            Session::put('sessionUuid', $this->sessionUuid);
        } else {
            $this->sessionUuid = Session::get('sessionUuid', fn() => Str::uuid());

            if (! Session::has('sessionUuid')) {
                Session::put('sessionUuid', $this->sessionUuid);
                Transaction::create(['uuid' => $this->sessionUuid]);
            }
        }
    }

    public function render()
    {
        return view('livewire.balance');
    }
}
