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
    public ?string $sessionUuid = null;

    protected $listeners = ['payment-received' => 'incrementBalance'];

    public function mount()
    {
        $this->setBalance();
        $this->setSessionToken();
    }

    public function hydrate()
    {
        $this->setSessionUuid();
        $this->setBalance();
    }

    public function incrementBalance(int $value): void
    {
        $this->balance += $value;

        if (Auth::guest()) {
            Transaction::whereUuid($this->sessionUuid)->update([
                'amount' => $this->balance
            ]);
        } else Auth::user()->update(['balance' => $this->balance]);

        $this->emit('increment-balance');
    }

    public function getCreditToken(): void
    {
        if (Auth::guest()) {
            $this->creditToken = Transaction::findOrCreateCreditToken(
                $this->sessionUuid
            );
        } else $this->creditToken = Auth::user()->creditToken;
    }

    private function setBalance()
    {
        if (Auth::guest()) {
            $this->balance =
                Transaction::firstWhere('uuid', $this->sessionUuid)->amount;
        } else $this->balance = Auth::user()->balance;
    }

    private function setSessionUuid()
    {
        if (Auth::guest()) {
            $this->sessionToken = session('sessionToken', fn() => Str::uuid());

                if (! session()->has('sessionUuid')) {
                    session(['sessionUuid' => $this->sessionUuid]);
                    Transaction::create(['uuid' => $this->sessionUuid]);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.balance');
    }
}
