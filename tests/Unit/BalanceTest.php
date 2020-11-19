<?php

namespace Tests\Unit;

use App\Http\Livewire\Balance;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    use DatabaseTransactions;

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

    public function testGuestBalanceIsZeroOnMount(): void
    {
        Livewire::test(Balance::class)->assertSet('balance', 0);
        $this->assertEquals(0, session('balance'));
    }

    public function balanceProvider(): array
    {
        return [
            [0], [1], [2], [3], [4], [5]
        ];
    }
}
