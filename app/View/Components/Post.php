<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Post extends Component
{
    // Post
    public array $post;

    /**
     * Create a new component instance.
     */
    public function __construct(array $post)
    {
        // Assign post to a public property
        $this->post = $post;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.post');
    }
}
