<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    public function __construct(
        public ?string $action = null,
        public string $method = 'POST',
        public ?string $id = null,
        public ?string $class = null,
        public bool $files = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view("components.form");
    }
}
