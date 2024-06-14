<?php

namespace App\Livewire\Partials;

use Countable;
use Livewire\Component;
use App\Helpers\CartManagement;

class Navbar extends Component
{
    public $total_count = 0;

    public function mount(){
        $cartItems = CartManagement::getCartItemsFromCookie();
        $this->total_count = is_array($cartItems) || $cartItems instanceof Countable ? count($cartItems) : 0;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
