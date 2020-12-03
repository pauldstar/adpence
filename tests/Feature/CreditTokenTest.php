<?php

namespace Tests\Feature;

use App\Http\Livewire\Balance;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class CreditTokenTest extends TestCase
{
    use DatabaseTransactions;

    public function testGuestCreditTokenWithNoCredit(): void
    {
        Livewire::test(Balance::class)
            ->call('getCreditToken')
            ->assertSet('creditToken', 'No credit, no token');
    }

    public function testGuestCreditTokenRepeatsOnSameSession(): void
    {
        $this->creditTokenRepeatsTest();
    }

    public function testUnusedMemberCreditTokenRepeats(): void
    {
        $this->creditTokenRepeatsTest(true);
    }

    private function creditTokenRepeatsTest(bool $loggedIn = false)
    {
        $loggedIn && $this->actingAs(User::factory()->create());

        $comp = Livewire::test(Balance::class);
        $comp->call('incrementBalance', 1);
        $comp->call('getCreditToken')->assertNotSet('creditToken', null);

        $token = $comp->creditToken;
        $comp->call('getCreditToken')->assertSet('creditToken', $token);
    }
}
