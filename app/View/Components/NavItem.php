<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavItem extends Component
{
    public $href;
    public $icon;
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($href = null, $icon = null, $text = null)
    {
        $this->href = $href;
        $this->icon = $icon;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav-item');
    }
}
