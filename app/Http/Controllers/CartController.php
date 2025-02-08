<?php

namespace App\Http\Controllers;

use App\Models\Coupan;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\where;


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

    public function apply_coupan_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
    
        if (isset($coupon_code)) {
            $coupon = Coupan::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
                ->first();
    
            if (!$coupon) { // Fix the logic here
                return redirect()->back()->with('error', 'Invalid Coupon Code');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
    
                $this->calculateDiscount();
    
                return redirect()->back()->with('success', 'Coupon has been applied successfully!');
            }
        } else {
            return redirect()->back()->with('error', 'Please enter a valid coupon code.');
        }
    }
    

    public function calculateDiscount()
    {
        $discount = 0;
        if(Session::has('coupon')){
            if(Session::get('coupon')['type'] == 'fixed')
            {
                $discount = Session::get('coupon')['value'];
            }
            else{
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
            }

            $subtotalafterdicount = Cart::instance('cart')->subtotal() -  $discount;
            $taxAfterDiscount = ($subtotalafterdicount * config('cart.tax'))/100;
            $totalAfterDiscount = $subtotalafterdicount+$taxAfterDiscount;
            
            Session::put('discounts',[

                'discount' => number_format(floatval($discount),2,'.',''),
                'subtotal' => number_format(floatval($subtotalafterdicount),2,'.',''),
                'tax' => number_format(floatval($taxAfterDiscount),2,'.',''),
                'total' => number_format(floatval($totalAfterDiscount),2,'.','')

            ]);
        }

    }

    public function remove_coupon_cart()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('error','Coupon has been removed');
    }
        
     
}
