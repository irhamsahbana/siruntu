<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Group extends Component
{
    public $col;
    public $label;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($col = null, $label = null)
    {
        $this->col = $col;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.group');
    }
}
