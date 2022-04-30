<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InSelect extends Component
{
    public $label;
    public $col;
    public $options;
    public $value;
    public $name;
    public $id;
    public $placeholder;
    public $required;
    public $multiple;
    public $disabled;
    public $readonly;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null,
        $col = null,
        $options = null,
        $value = null,
        $name = null,
        $id = null,
        $placeholder = null,
        $required = null,
        $multiple = null,
        $disabled = null,
        $readonly = null
    ) {
        $this->label = $label;
        $this->col = $col;
        $this->options = $options;
        $this->value = $value;
        $this->name = $name;
        $this->id = $id;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.in-select');
    }
}
