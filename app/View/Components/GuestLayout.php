<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public bool $card;
    public string $maxWidth;

    public function __construct(bool $card = true, string $maxWidth = 'md')
    {
        $this->card = $card;
        $this->maxWidth = $maxWidth;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
