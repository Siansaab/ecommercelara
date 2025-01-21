<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class Shopcontroler extends Controller
{
    public function index()
    {
        $product = Product::orderBy('created_at','desc')->paginate(12);
        return view('shop',compact('product'));
    }
}
