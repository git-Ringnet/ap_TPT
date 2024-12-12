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
    public $name;
    public $page;

    /**
     * Tạo mới component.
     *
     * @param  string  $id
     * @param  string  $title
     * @return void
     */
    public function __construct($id, $title = 'Modal Title', $dataProduct = null, $page = null , $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->dataProduct = $dataProduct;
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.add-product-modal');
    }
}
