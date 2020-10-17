<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Play extends Component
{
    public function play()
    {
        $this->emit('payment-received', 25);
    }

    public function render()
    {
        return view('livewire.play');
    }
}
