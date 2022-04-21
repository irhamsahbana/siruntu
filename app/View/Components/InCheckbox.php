<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InCheckbox extends Component
{
    public $col;

    public $id;
    public $label;
    public $value;
    public $name;
    public $isChecked;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id = null,
        $label = null,
        $value = null,
        $name = null,
        $isChecked = null
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->name = $name;
        $this->isChecked = $isChecked;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.in-checkbox');
    }
}
