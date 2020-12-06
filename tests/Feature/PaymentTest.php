<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @dataProvider balanceProvider
     * @param int $balance
     * @param int $amount
     * @param int $status
     */
    public function testMemberPayment(int $balance, int $amount, int $status)
    {
        $user = User::factory(['balance' => $balance])->create();
        $this->actingAs($user);

        $token = $user->creditToken;

        $response = $this->postJson('api/payment', [
            'token' => $token,
            'amount' => $amount,
            'vendor' => 'testing'
        ]);

        $response->assertStatus($status);

        $user = $user->refresh();
        $transaction = Transaction::firstWhere('uuid', $token);

        if ($status === 200) {
            $this->assertEquals($amount, $transaction->amount);
            $this->assertEquals($balance - $amount, $user->balance);
        } else {
            $this->assertNull($transaction->amount);
            $this->assertEquals($balance, $user->balance);
        }
    }

    /**
     * @dataProvider balanceProvider
     * @param int $balance
     * @param int $amount
     * @param int $status
     */
    public function testGuestPayment(int $balance, int $amount, int $status)
    {
        $uuid = Str::uuid();
        Transaction::createCreditToken($uuid, $balance);

        $response = $this->postJson('api/payment', [
            'token' => $uuid,
            'amount' => $amount,
            'vendor' => 'testing'
        ]);

        $response->assertStatus($status);

        $transaction = Transaction::firstWhere('uuid', $uuid);

        if ($status === 200) {
            $this->assertEquals($balance - $amount, $transaction->amount);
        } else {
            $this->assertEquals($balance, $transaction->amount);
        }
    }

    public function balanceProvider()
    {
        $data = [];

        for ($bal = 0; $bal < 5; $bal++) {
            for ($amt = 0; $amt < 10; $amt++) {
                $data[] = [$bal, $amt, $bal < $amt ? 401 : 200];
            }
        }

        return $data;
    }
}
