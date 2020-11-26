<?php

namespace Tests\Feature;

use App\Http\Livewire\Balance;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\MockSocialite;
use Tests\TestCase;

class CreditTokenTest extends TestCase
{
    use DatabaseTransactions;

    public function testGuestCreditTokenWithNoCredit(): void
    {
        Livewire::test(Balance::class)
            ->call('getCreditToken')
            ->assertSet('creditToken', null);
        // Session::has() not working, so using get()
        $this->assertContains('uuidError', Session::get('_flash.new'));
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
//
//    /**
//     * @dataProvider balanceProvider
//     * @param int $val
//     */
//    public function testIncrementMemberBalance(int $val): void
//    {
//        $this->actingAs(User::factory()->create());
//        $balanceComp = Livewire::test(Balance::class);
//        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val);
//        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val * 2);
//
//        $this->assertTrue(User::where([
//            'id' => Auth::id(), 'balance' => $val * 2
//        ])->exists());
//    }
//
//    /**
//     * @dataProvider balanceProvider
//     * @param int $val
//     */
//    public function testIncrementGuestBalance(int $val): void
//    {
//        $balanceComp = Livewire::test(Balance::class);
//        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val);
//        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val * 2);
//
//        $this->assertTrue(Transaction::where([
//            'uuid' => session('sessionUuid'),
//            'amount' => $val * 2
//        ])->exists());
//    }
//
//    public function testNewMemberBalanceIsZeroOnMount(): void
//    {
//        $this->actingAs(User::factory()->create());
//        $balanceComp = Livewire::test(Balance::class);
//        $balanceComp->assertSet('balance', 0);
//
//        $this->assertTrue(User::where([
//            'id' => Auth::id(),
//            'balance' => 0
//        ])->exists());
//    }
//
//    public function testGuestBalanceIsZeroOnMount(): void
//    {
//        Livewire::test(Balance::class)->assertSet('balance', 0);
//        $this->assertEquals(0, session('balance'));
//    }
//
//    public function balanceProvider(): array
//    {
//        return [
//            [0], [1], [2], [3], [4], [5]
//        ];
//    }
}
