<?php

namespace Tests\Unit;

use App\Http\Livewire\Balance;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SessionTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testNewSessionTokenIsSet(): void
    {
        self::flushSession();
        Livewire::test(Balance::class)->assertNotSet('sessionToken', null);
        self::assertTrue(session()->has('sessionToken'));
        self::assertTrue(Transaction::whereToken(session('sessionToken'))->exists());
    }
}
