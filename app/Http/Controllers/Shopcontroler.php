<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class Shopcontroler extends Controller
{
    public function index()
    {
        $product = Product::orderBy('created_at','desc')->paginate(1);
        return view('shop',compact('product'));
    }

    public function product_details($product_slug){

        $product = Product::where('slug',$product_slug)->first();
        $products = Product::where('slug','<>',$product_slug)->get()->take(8);
        return view('details',compact('product','products'));

    }
}
