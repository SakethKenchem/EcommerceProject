<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement{
    //add item to cart
    static public function addItemToCart($product_id){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item= null;

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }
        if($existing_item !== null){
            $cart_items[$existing_item]['quantity'] ++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        }else{
            $product = Product::find($product_id);
            $cart_items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_amount' => $product->price,
                'quantity' => 1,
                'total_amount' => $product->price
            ];
        }
    }


    //remove item from cart
    static function removeItemFromCart($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }


    //add cart items to cookie
    static function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60*24*30);
    }

    //clear cart items from cookie
    static function clearCartItemsFromCookie(){
        Cookie::queue(Cookie::forget('cart_items'));
    }


    //get all cart items from cookie
    static function getCartItemsFromCookie(){
        $cart_items = self::getCartItemsFromCookie();
        $existing_cart_item = null;
        
        if (is_array($cart_items) || is_object($cart_items)) {
            self::addCartItemsToCookie($cart_items);
        
            foreach($cart_items as $key => $cart_item){
                if($cart_item['product_id'] == $product_id){
                    $existing_cart_item = $key;
                    break;
                }
            }
        }
    } 

//increment cart item quantity
static function incrementCartItemQuantity($product_id){
    $cart_items = self::getCartItemsFromCookie();

    foreach($cart_items as $key => $item){
        if($item['product_id'] == $product_id){
            $cart_items[$key]['quantity'] ++;
            $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            break;
        }
    }
}
    
    //decrement cart item quantity
    static function decrementCartItemQuantity($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity'] --;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
            }
        }
        
        self::addCartItemsToCookie($cart_items);
        return $cart_items;

    }

    //calculate grand total

    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
        
    }
}