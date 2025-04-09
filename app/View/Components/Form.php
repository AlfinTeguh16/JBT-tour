<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    public $type;
    public $name;
    public $label;
    public $placeholder;
    public $value;
    public $required;
    public $options; // Untuk menambahkan pilihan seperti radio atau checkbox

    /**
     * Create a new component instance.
     *
     * @param string $type The input type (text, textarea, checkbox, etc.)
     * @param string $name The input name
     * @param string $label The input label
     * @param string $placeholder The input placeholder
     * @param string $value The input value
     * @param bool $required Whether the input is required
     * @param array|null $options Options for variants like checkboxes or radios
     */
    public function __construct(
        $type = "text",
        $name,
        $label = "",
        $placeholder = "",
        $value = "",
        $required = false,
        $options = null // opsional untuk radio, checkbox
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->required = $required;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.form");
    }
}
