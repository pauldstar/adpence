<?php

namespace Tests\Feature;

use App\Http\Livewire\Play;
use App\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Video::create(['url' => 'https://www.youtube.com/embed/Jfrjeg26Cwk']);
    }

    public function testVideoPlay()
    {
        Livewire::test(Play::class)
            ->call('play')
            ->assertEmitted('play-video');

        $this->assertTrue(Session::has('videoStarted'));
    }

    /**
     * @dataProvider videoPlayTimesProvider
     * @param int $start
     * @param string $expectedEmit
     */
    public function testVideoStop(int $start, string $expectedEmit)
    {
        Session::put('videoStarted', time() - $start);

        Livewire::test(Play::class)
            ->emit('video-stopped')
            ->assertEmitted($expectedEmit);

        $this->assertFalse(Session::has('videoStarted'));
    }

    /**
     * @return array
     */
    public function videoPlayTimesProvider(): array
    {
        $data = [];

        $maxElapsed = Video::$minPlayTime * 2;

        for ($e = 0; $e <= $maxElapsed; $e += 5) {
            $data[] = [
                $e,
                $e < Video::$minPlayTime ? 'no-payment' : 'increment-balance'
            ];
        }

        return $data;
    }
}
