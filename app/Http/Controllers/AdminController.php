<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Session;


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
}
