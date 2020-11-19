<?php

namespace Tests\Unit;

use App\Http\Livewire\Balance;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class SessionUuidTest extends TestCase
{
    use DatabaseTransactions;

    private TestableLivewire $component;
    private string $sessionUuid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->component = Livewire::test(Balance::class);
    }

    public function testSessionUuid(): void
    {
        $this->freshSession();
        $this->expiredServerUuid();
        $this->refreshedClientUuid();
    }

    /**
     * Testing fresh session: with no server UUID or client UUID
     */
    private function freshSession()
    {
        $this->component->assertNotSet('sessionUuid', null);

        $this->assertTrue(session()->has('sessionUuid'));
        $this->sessionUuid = session('sessionUuid');

        $this->assertTrue(Transaction::whereUuid($this->sessionUuid)->exists());
    }

    /**
     * Testing expired server uuid, but still having client uuid
     */
    private function expiredServerUuid()
    {
        $this->flushSession();

        $this->assertEmpty(session('sessionUuid'));
        $this->component->set('balance', 1)->assertSet('sessionUuid', $this->sessionUuid);

        $this->assertEquals($this->sessionUuid, session('sessionUuid'));
    }

    /**
     * Testing client refresh on current server session
     */
    private function refreshedClientUuid()
    {
        $this->component->set('sessionUuid', null)
            ->call('incrementBalance', 25)
            ->assertSet('sessionUuid', $this->sessionUuid);

        $this->assertEquals($this->sessionUuid, session('sessionUuid'));
    }
}
