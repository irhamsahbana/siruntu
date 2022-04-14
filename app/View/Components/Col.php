<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Col extends Component
{
    public $col;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($col = null)
    {
        $this->col = $col;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.col');
    }
}
