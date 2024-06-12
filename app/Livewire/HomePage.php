<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title('Home Page - ECommerce')]

class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $products = Product::where('is_active', 1)->where('on_sale', 1)->get();
        return view('livewire.home-page', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
        ]);
        
    }
}