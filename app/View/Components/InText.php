<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InText extends Component
{
    public $col;

    public $id;
    public $label;
    public $value;
    public $type;
    public $placeholder;
    public $required;
    public $disabled;
    public $readonly;
    public $step;
    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $col = null,
        $id = null,
        $label = null,
        $value = null,
        $type = null,
        $placeholder = null,
        $required = null,
        $disabled = null,
        $readonly = null,
        $step = null,
        $name = null
    ) {
        $this->col = $col;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->step = $step;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.in-text');
    }
}
