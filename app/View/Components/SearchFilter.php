<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchFilter extends Component
{
    public $keywords;
    public $filters;

    public function __construct($keywords = '', $filters = [])
    {
        $this->keywords = $keywords;
        $this->filters = $filters;
    }

    public function render()
    {
        return view('components.search-filter');
    }
}
