<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardCollapsible extends Component
{
    public $title;
    public $col;
    public $collapse;
    public $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null,
        $col = null,
        $collapse = null,
        $color = null
    )
    {
        $this->title = $title;
        $this->col = $col;
        $this->collapse = $collapse;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-collapsible');
    }
}
