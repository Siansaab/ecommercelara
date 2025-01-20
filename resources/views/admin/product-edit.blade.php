@extends('layouts.admin')
@section('content')
    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Products</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit product</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action=" ">
                @csrf
                <div class="wg-box">
                    <!-- Product Name -->
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" value="{{ $product->name }}" required>
                    </fieldset>
                    @error('name') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <!-- Slug -->
                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" value="{{ $product->slug }}" required>
                    </fieldset>
                    @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <!-- Category and Brand -->
                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="category_id" required>
                                    <option value="">Choose category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"{{ $product->category_id == $category->id  ? "Selected":""}} {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                        <fieldset class="brand">
                            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="brand_id" required>
                                    <option value="">Choose Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id  ? "Selected":""}} {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                    </div>
            
                    <!-- Short Description -->
                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" required>{{ $product->short_description }}</textarea>
                    </fieldset>
                    @error('short_description') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <!-- Full Description -->
                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Description" required>{{ $product->description }}</textarea>
                    </fieldset>
                    @error('description') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>
            
                <!-- Image Upload -->
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                           

                            @if($product->image)
                            <div class="item" id="imgpreview"  >
                                <img src="{{asset('uploads/products/thumbails/')}}/{{ $product->image }}" class="effect8" alt="">
                            </div>
                            @endif


                            
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                            browse</span></span>

                                            
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                      
                    </fieldset>
                    @error('image') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <!-- Gallery Images Upload -->
                    <fieldset>
                        <div class="body-title mb-10">Upload Gallery Images</div>

                        <div class="upload-image mb-16">
                            @if($product->images)
                            @foreach(explode(',',$product->images) as $img)
                            <div class="item gitems">
                                <img src="{{asset('uploads/products')}}/{{trim($img)}}" alt="">
                            </div>       
                            @endforeach
                        @endif                                          -->
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*"
                                        multiple="">
                                </label>
                            </div>
                        </div>

                         
                    </fieldset>
                    @error('images') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>
            
                <!-- Prices and Stock -->
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="regular_price" placeholder="Enter regular price" value="{{ $product->regular_price }}" required>
                    </fieldset>
                    @error('regular_price') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="sale_price" placeholder="Enter sale price" value="{{ $product->sale_price }}" required>
                    </fieldset>
                    @error('sale_price') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input type="text" name="sku" placeholder="Enter SKU" value="{{ $product->sku }}" required>
                    </fieldset>
                    @error('sku') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input type="text" name="quantity" placeholder="Enter quantity" value="{{ $product->quantity }}" required>
                    </fieldset>
                    @error('quantity') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>
            
                <div class="wg-box">
                    <!-- Stock and Featured -->
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock Status</div>
                        <select name="stock_status" required>
                            <option value="instock" {{ $product->stock_status == "instock" ? "Selected":""}}>InStock</option>
                            <option value="outofstock" {{ $product->stock_status == "outofstock" ? "Selected":""}}>Out of Stock</option>
                        </select>
                    </fieldset>
                    @error('stock_status') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
            
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <select name="featured" required>
                            <option value="0" {{ $product->featured == "0" ? "Selected":""}}>No</option>
                            <option value="1" {{ $product->featured == "1" ? "Selected":""}}>Yes</option>
                        </select>
                    </fieldset>
                    @error('featured') <span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                </div>
            
                <!-- Submit Button -->
                <button class="tf-button w-full" type="submit">Add Product</button>
            </form>
            
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>


    @push('script')
    <script>
        $(function() {
            // Preview the selected image
            $('#myFile').on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
    
            $('#gFile').on("change", function(e) {
                const gphotos = this.files;
                $.each(gphotos, function(key, val) {
                    // Create a new div with the image preview
                    $("#galUpload").prepend(`
                        <div class="item gitems">
                            <img src="${URL.createObjectURL(val)}" />
                        </div>
                    `);
                });
            });
    
            // Automatically generate slug from the name input
            $("input[name='name']").on("input", function() {
                $("input[name='slug']").val(stringToSlug($(this).val()));
            });
    
            // Function to convert text to slug
            function stringToSlug(text) {
                return text
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric characters with a hyphen
                    .replace(/^-+|-+$/g, '');   // Remove leading and trailing hyphens
            }
        });
    </script>
   

    
@endpush
@endsection
