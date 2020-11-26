<?php

namespace Tests\Feature;

use App\Http\Livewire\Balance;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\MockSocialite;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    use DatabaseTransactions;
    use MakesHttpRequests;
    use MockSocialite;

    public function testGuestBalanceIncrementsMemberBalanceOnLogin(): void
    {
        $balanceComp = Livewire::test(Balance::class)->call('incrementBalance', 100);

        $this->mockSocialiteFacade(User::factory()->create(['balance' => 100]));
        $this->get('login/facebook/callback')->assertRedirect('/');

        $balanceComp->call('incrementBalance', 0)->assertSet('balance', 200);

        $this->assertTrue(User::where(['id' => Auth::id(), 'balance' => 200])->exists());
    }

    /**
     * @dataProvider balanceProvider
     * @param int $val
     */
    public function testIncrementMemberBalance(int $val): void
    {
        $this->actingAs(User::factory()->create());
        $balanceComp = Livewire::test(Balance::class);
        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val);
        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val * 2);

        $this->assertTrue(User::where([
            'id' => Auth::id(), 'balance' => $val * 2
        ])->exists());
    }

    /**
     * @dataProvider balanceProvider
     * @param int $val
     */
    public function testIncrementGuestBalance(int $val): void
    {
        $balanceComp = Livewire::test(Balance::class);
        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val);
        $balanceComp->call('incrementBalance', $val)->assertSet('balance', $val * 2);

        $this->assertTrue(Transaction::where([
            'uuid' => session('sessionUuid'),
            'amount' => $val * 2
        ])->exists());
    }

    public function testNewMemberBalanceIsZeroOnMount(): void
    {
        $this->actingAs(User::factory()->create());
        $balanceComp = Livewire::test(Balance::class);
        $balanceComp->assertSet('balance', 0);

        $this->assertTrue(User::where([
            'id' => Auth::id(),
            'balance' => 0
        ])->exists());
    }

    public function testGuestBalanceIsZeroOnMount(): void
    {
        Livewire::test(Balance::class)->assertSet('balance', 0);
        $this->assertEquals(0, session('balance'));
    }

    public function balanceProvider(): array
    {
        return [
            [1], [2], [3], [4], [5]
        ];
    }
}
