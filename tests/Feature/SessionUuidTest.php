<?php

namespace Tests\Feature;

use App\Http\Livewire\Balance;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\MockSocialite;
use Tests\TestCase;

class SessionUuidTest extends TestCase
{
    use DatabaseTransactions;
    use MockSocialite;

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
        $this->uuidDeletedAfterLogin();
    }

    /**
     * Testing fresh session: with no server UUID or client UUID
     */
    private function freshSession()
    {
        $this->component->assertSet('sessionUuid', null);
        $this->assertFalse(Session::has('sessionUuid'));

        $this->component->call('incrementBalance', 1);
        $this->component->assertNotSet('sessionUuid', null);

        $this->assertTrue(Session::has('sessionUuid'));
        $this->sessionUuid = Session::get('sessionUuid');

        $this->assertTrue(Transaction::whereUuid($this->sessionUuid)->exists());
    }

    /**
     * Testing expired server uuid, but still having client uuid
     */
    private function expiredServerUuid()
    {
        $this->flushSession();

        $this->assertEmpty(Session::get('sessionUuid'));

        $this->component
            ->call('incrementBalance', 1)
            ->assertSet('sessionUuid', $this->sessionUuid);

        $this->assertEquals($this->sessionUuid, Session::get('sessionUuid'));
    }

    /**
     * Testing client refresh on current server session
     */
    private function refreshedClientUuid()
    {
        $this->component->set('sessionUuid', null)
            ->call('incrementBalance', 25)
            ->assertSet('sessionUuid', $this->sessionUuid);

        $this->assertEquals($this->sessionUuid, Session::get('sessionUuid'));
    }

    private function uuidDeletedAfterLogin()
    {
        $this->mockSocialiteFacade(User::factory()->create());
        $this->get('login/facebook/callback');
        $this->assertFalse(Session::has('sessionUuid'));
        $this->assertTrue(Transaction::whereUuid($this->sessionUuid)->exists());
    }
}
