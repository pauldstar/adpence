<?php

namespace App\Http\Livewire;

use App\Video;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Play extends Component
{
    protected array $listeners = [
        'video-stopped' => 'stopVideo'
    ];

    public function play()
    {
        $url = Video::inRandomOrder()->first()->url;

        Session::put('videoStarted', time());

        $this->emit(
            'play-video',
            $url . '?autoplay=1&amp;modestbranding=1&amp;showinfo=0'
        );
    }

    public function stopVideo()
    {
        $start = Session::pull('videoStarted');
        $elapsed = time() - $start;

        if ($elapsed >= Video::$minPlayTime) {
            $this->emit('increment-balance', Video::$paymentAmount);
        } else {
            $this->emit('no-payment');
        }
    }

    public function render()
    {
        return view('livewire.play');
    }
}
