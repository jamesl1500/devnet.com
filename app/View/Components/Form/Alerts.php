<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alerts extends Component
{
    // Alert type
    public string $type;

    // Alert Message
    public string $message;

    // Alert array of messages
    public array $messages;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type, string $message, array $messages = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->messages = $messages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.alerts');
    }
}
