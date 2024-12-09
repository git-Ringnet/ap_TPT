<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddProductModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;
    public $title;
    public $dataProduct;

    /**
     * Tạo mới component.
     *
     * @param  string  $id
     * @param  string  $title
     * @return void
     */
    public function __construct($id, $title = 'Modal Title', $dataProduct = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->dataProduct = $dataProduct;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.add-product-modal');
    }
}
