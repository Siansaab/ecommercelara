<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupan;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Colors\Rgb\Channels\Red;
use PHPUnit\Event\Test\ComparatorRegisteredSubscriber;

class AdminController extends Controller
{
    public function admin()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brands()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'slug' => 'required|unique:brands,slug',
                'image' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048'
            ]
        );

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Check if the image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            // Generate the brand thumbnail
            $this->generatebrandthumbail($image, $file_name);

            // Set the image field
            $brand->image = $file_name;
        }

        // Save the brand
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand added successfully');
    }

 
    public function brand_edit($id)
{
    $brand = Brand::findOrFail($id);
    return view('admin.brand-edit', compact('brand'));
}


public function brand_update(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:brands,slug,' . $request->id,
        'image' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048'
    ]);

    // Find the brand by ID from the request
    $brand = Brand::findOrFail($request->id);  // Corrected to access id from the request

    // Update brand name and slug
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);

    // Check if the image is uploaded
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;

        // Generate the brand thumbnail (assuming this method exists in the controller)
        $this->generatebrandthumbail($image, $file_name);

        // Set the image field
        $brand->image = $file_name;
    }

    // Save the brand
    $brand->save();

    // Redirect with a success message
    return redirect()->route('admin.brands')->with('status', 'Brand updated successfully');
}


    public function generatebrandthumbail($image, $imagename)
    {
        $destinationpath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationpath . '/' . $imagename);
    }

    public function brand_delete($id){
        $brand = Brand::findOrFail($id); 
        if(File::exists(public_path('uploads/brand').'/'.$brand->image))
        {
            File::delete(public_path('uploads/brand').'/'.$brand->image);
        } // Corrected to access id from the request
        $brand->delete();
        return redirect()->route('admin.brands')->with("status",'Brand has been Delete Successfully');

    }

    public function category()
    {
        $Category = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.category', compact('Category'));
    }
    public function add_category()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'slug' => 'required|unique:categories,slug',
                'image' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048'
            ]
        );

        $Category = new Category();
        $Category->name = $request->name;
        $Category->slug = Str::slug($request->name);

        // Check if the image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            // Generate the brand thumbnail
            $this->generatecategorythumbail($image, $file_name);

            // Set the image field
            $Category->image = $file_name;
        }

        // Save the brand
        $Category->save();

        return redirect()->route('admin.category')->with('status', 'Category added successfully');
    }


    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }
    
    
    public function category_update(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048'
        ]);
    
        // Find the brand by ID from the request
        $category = Category::findOrFail($request->id);  // Corrected to access id from the request
    
        // Update brand name and slug
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
    
        // Check if the image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
    
            // Generate the brand thumbnail (assuming this method exists in the controller)
            $this->generatecategorythumbail($image, $file_name);
    
            // Set the image field
            $category->image = $file_name;
        }
    
        // Save the brand
        $category->save();
    
        // Redirect with a success message
        return redirect()->route('admin.category')->with('status', 'category updated successfully');
    }
    

    public function generatecategorythumbail($image, $imagename)
    {
        $destinationpath = public_path('uploads/category');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationpath . '/' . $imagename);
    }

    public function category_delete($id){
        $category = Category::findOrFail($id); 
        if(File::exists(public_path('uploads/category').'/'.$category->image))
        {
            File::delete(public_path('uploads/category').'/'.$category->image);
        } // Corrected to access id from the request
        $category->delete();
        return redirect()->route('admin.category')->with("status",'category has been Delete Successfully');

    }

    public function products()
    {
        $products = Product::orderBy('id','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function add_products()
    {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
    }

    public function product_store(Request $request)
{

    
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug',
        'short_description' => 'required',
        'description' => 'required', // Fixed typo
        'regular_price' => 'required',
        'sale_price' => 'required',
        'sku' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'image' => 'required|mimes:jpg,png,webp,jpeg|max:2048',
        'quantity' => 'required',
        'category_id' => 'required',
        'brand_id' => 'required',
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = Str::slug($request->slug);
    $product->short_description = $request->short_description;
    $product->description = $request->description; // Fixed typo
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->sku = $request->sku;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Handle main product image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->generateproductthumbailImage($image, $imageName);
        $product->image = $imageName;
    }

    // Handle gallery images
    $gallery_arr = [];
    $gallery_images = "";
    $counter = 1;

    if ($request->hasFile('images')) {
        $allowedfileextension = ['jpg', 'jpeg', 'png', 'webp'];
        $files = $request->file('images'); // Fixed
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $gcheck = in_array($gextension, $allowedfileextension);
            if ($gcheck) {
                $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                $this->generateproductthumbailImage($file, $gfileName);
                array_push($gallery_arr, $gfileName);
                $counter++;
            }
        }
        $gallery_images = implode(',', $gallery_arr); // Fixed
    }

    $product->images = $gallery_images;
    $product->save(); // Fixed

    return redirect()->route('admin.products')->with('status', 'Product has been added successfully');
}

public function generateproductthumbailImage($image, $imagename)
{
    $destinationpaththumbnail = public_path('uploads/products/thumbails');
    $destinationpath = public_path('uploads/products');

    $img = Image::read($image->path());
    $img->cover(540, 689, "top");
    $img->resize(540, 689, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationpath . '/' . $imagename);

    $img->resize(104, 104, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationpaththumbnail . '/' . $imagename);
}

public function product_edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
    return view('admin.product-edit', compact('product','categories','brands'));
}

public function product_update(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id,
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'sku' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'image' => 'nullable|mimes:jpg,png,jpeg,webp|max:2048',
        'quantity' => 'required',
        'category_id' => 'required',
        'brand_id' => 'required',
    ]);

    $product = Product::find($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->slug);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->sku = $request->sku;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Process the main image
    if ($request->hasFile('image')) {
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbails') . '/' . $product->image);
        }
        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->generateproductthumbailImage($image, $imageName);
        $product->image = $imageName;
    }

    // Handle gallery images
    $gallery_arr = [];
    $gallery_images = "";
    $counter = 1;

    if ($request->hasFile('images')) {
        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbails') . '/' . $ofile);
            }
        }

        $allowedfileextension = ['jpg', 'jpeg', 'png', 'webp'];
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $gcheck = in_array($gextension, $allowedfileextension);
            if ($gcheck) {
                $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                $this->generateproductthumbailImage($file, $gfileName);
                array_push($gallery_arr, $gfileName);
                $counter++;
            }
        }
        $gallery_images = implode(',', $gallery_arr);
        $product->images = $gallery_images; // Update gallery images
    } else {
        $product->images = $product->images; // Retain existing images
    }

    $product->save();

    return redirect()->route('admin.products')->with('status', 'Product has been updated successfully');
}

public function product_delete($id)
{
    $product = Product::find($id);
    if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
        File::delete(public_path('uploads/products') . '/' . $product->image);
    }
    if (File::exists(public_path('uploads/products/thumbails') . '/' . $product->image)) {
        File::delete(public_path('uploads/products/thumbails') . '/' . $product->image);
    }

    foreach (explode(',', $product->images) as $ofile) {
        if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
            File::delete(public_path('uploads/products') . '/' . $ofile);
        }
        if (File::exists(public_path('uploads/products/thumbails') . '/' . $ofile)) {
            File::delete(public_path('uploads/products/thumbails') . '/' . $ofile);
        }
    }

    $product->delete(); 
    return redirect()->route('admin.products')->with('status', 'Product has been delete successfully');                                                                        
}

    public function coupans(){
        $coupans = Coupan::orderBy('expiry_date', 'DESC')->paginate(10);
        return view('admin.coupans',compact('coupans'));
    }


    public function coupans_add()
    {
        return view('admin.coupans-add');
    }


    public function coupans_store(Request $request)
    {
        $request->validate(
            [
                'code' => 'required',
                'type' => 'required',
                'value' => 'required|numeric',
                'cart_value' => 'required|numeric',
                'expiry_date' => 'required|date'
                
            ]
        );

        $coupan = new Coupan();
        $coupan->code = $request->code;
        $coupan->type = $request->type;
        $coupan->value = $request->value;
        $coupan->cart_value = $request->cart_value;
        $coupan->expiry_date = $request->expiry_date;

       

        // Save the brand
        $coupan->save();

        return redirect()->route('admin.coupans')->with('status', 'coupan added successfully');
    }

    public function coupans_edit($id)
    {
        $coupan = Coupan::findOrFail($id);
        return view('admin.coupans-edit', compact('coupan'));
    }
    

    public function coupans_update(Request $request)
    {
        // Validate the incoming request data
        $request->validate(
            [
                'code' => 'required',
                'type' => 'required',
                'value' => 'required|numeric',
                'cart_value' => 'required|numeric',
                'expiry_date' => 'required|date'
                
            ]
            );
    
        $coupan = Coupan::findOrFail($request->id);  

        $coupan->code     =        $request->code;
        $coupan->type     =        $request->type;
        $coupan->value    =       $request->value;
        $coupan->cart_value =  $request->cart_value;
        $coupan->expiry_date = $request->expiry_date;
       
        $coupan->save();
    
        // Redirect with a success message
        return redirect()->route('admin.coupans')->with('status', 'coupans updated successfully');
    }

    public function coupans_delete($id){
        $Coupans = Coupan::findOrFail($id); 
      
        $Coupans->delete();
        return redirect()->route('admin.coupans')->with("error",'coupans has been Delete Successfully');

    }
}



