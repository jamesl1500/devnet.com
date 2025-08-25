<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
{
    // Header
    protected $header;

    // Subheader
    protected $subheader;

    // Image
    protected $image;

    /**
     * Create a new component instance.
     */
    public function __construct( $header, $subheader, $image )
    {
        $this->header = $header;
        $this->subheader = $subheader;
        $this->image = $image;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.hero', [
            'header' => $this->header,
            'subheader' => $this->subheader,
            'image' => $this->image,
        ]);
    }
}
