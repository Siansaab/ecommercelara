<?php

namespace App\Http\Controllers;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
 
use App\Models\Product;

class WishlistController extends Controller
{

    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist',compact('items'));
    }
    public function addToWishlist(Request $request)
    {
        Cart::instance('wishlist')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price
        )->associate(Product::class);

        return redirect()->back()->with('message', 'Added to Wishlist!');
    }

    public function remove_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId); // Remove item by rowId
        return redirect()->back()->with('success', 'Item removed from wishlist.');
    }

    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId); // Remove item by rowId
        Cart::instance('cart')->add(
            $item->id,
            $item->name,
            $item->qty,
            $item->price
        )->associate('App\Models\Product'); // Associate the product model
        return redirect()->back();
    }
}
