<?php

namespace App\Livewire;

use livewire;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title ('CategoriesPage - ECommerce')]

class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.categories-page', [
            'categories' => $categories
        ]);
    }
}
