<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // Display the Cart Page
    public function index()
    {
        $items = Cart::instance('cart')->content(); // Get cart contents
        $total = Cart::instance('cart')->total(); // Total cart price
        return view('cart', compact('items', 'total')); // Return cart view
    }

    // Add Item to Cart
    public function add_to_cart(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        // Add item to the cart
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price
        )->associate('App\Models\Product'); // Associate the product model

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    // Remove Item from Cart
    public function remove_from_cart($rowId)
    {
        Cart::instance('cart')->remove($rowId); // Remove item by rowId
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    // Clear the Entire Cart
    public function clear_cart()
    {
        Cart::instance('cart')->destroy(); // Clear all items from the cart
        return redirect()->back()->with('success', 'Cart cleared.');
    }


    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        if ($product) {
            $qty = $product->qty + 1;
            Cart::instance('cart')->update($rowId, $qty);
        }

        return redirect()->back()->with('success', 'Quality updated  cart.');
    }

    /**
     * Decrease the quantity of a cart item.
     */
    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        if ($product) {
            $qty = max($product->qty - 1, 1); // Prevent quantity from going below 1
            Cart::instance('cart')->update($rowId, $qty);
        }

        return redirect()->back()->with('success', 'Quality updated  cart.');
    }
}
