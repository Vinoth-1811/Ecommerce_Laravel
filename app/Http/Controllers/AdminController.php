<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    // Admin Dashboard Brand

    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.add-brand');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);
        $image = $request->file('image');
        $file_extension = $request->file('image')->getClientOriginalExtension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->generateBrandThumbnail($image, $file_name);
        $brand->image = $file_name;
        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand has been added successfully!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.edit-brand', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/brands/'.$brand->image))) {
                File::delete(public_path('uploads/brands/'.$brand->image));
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->generateBrandThumbnail($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand has been updated successfully!');
    }

    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);
        if (File::exists(public_path('uploads/brands/'.$brand->image))) {
            File::delete(public_path('uploads/brands/'.$brand->image));
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('success', 'Brand has been deleted successfully!');
    }

    public function generateBrandThumbnail($image, $imageName)
    {
        $destinationFilePath = public_path('uploads\brands');
        $image->move($destinationFilePath, $imageName);
        $image = Image::read($destinationFilePath.'/'.$imageName);
        $image->cover(124, 124, 'top');
        $image->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationFilePath.'/'.$imageName);
    }

    // Admin Dashboard Category
    public function categories()
    {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.add-category', compact('categories'));
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $category->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $request->file('image')->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->generateCategoryThumbnail($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category has been added successfully!');
    }

    public function generateCategoryThumbnail($image, $imageName)
    {
        $destinationFilePath = public_path('uploads\categories');
        $image->move($destinationFilePath, $imageName);
        $image = Image::read($destinationFilePath.'/'.$imageName);
        $image->cover(124, 124, 'top');
        $image->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationFilePath.'/'.$imageName);
    }

    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.edit-category', compact('category', 'categories'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $category->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories/'.$category->image))) {
                File::delete(public_path('uploads/categories/'.$category->image));
            }
            $image = $request->file('image');
            $file_extension = $request->file('image')->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->generateCategoryThumbnail($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category has been updated successfully!');
    }

    public function category_delete($id)
    {
        $category = Category::findOrFail($id);
        if (File::exists(public_path('uploads/categories/'.$category->image))) {
            File::delete(public_path('uploads/categories/'.$category->image));
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category has been deleted successfully!');
    }

    // Admin Dashboard Product
    public function products()
    {
        $products = Product::orderBy('id','DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function add_product()
    {
        $categories = Category::select('id', 'name')->orderBy('name','ASC')->get();
        $brands = Brand::select('id', 'name')->orderBy('name','ASC')->get();
        return view('admin.add-product', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required',
            'stock_status' => 'required|in:instock,outofstock',
            'is_featured' => 'required|boolean',
            'quantity' => 'required|integer',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = new Product;
        $product->name = $validated['name'];
        $product->slug = Str::slug($validated['slug']);
        $product->short_description = $validated['short_description'];
        $product->description = $validated['description'];
        $product->regular_price = $validated['regular_price'];
        $product->sale_price = $validated['sale_price'];
        $product->SKU = $validated['SKU'];
        $product->stock_status = $validated['stock_status'];
        $product->is_featured = $validated['is_featured'];
        $product->quantity = $validated['quantity'];
        $product->category_id = $validated['category_id'];
        $product->brand_id = $validated['brand_id'];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->generateProductGalleryThumbnail($image, $file_name);
            $product->image = $file_name;
        }

        // Handle gallery images
        $gallery_images = [];
        if ($request->hasFile('images')) {
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
            $counter = 1;

            foreach ($request->file('images') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());

                if (in_array($extension, $allowedExtensions)) {
                    $filename = Carbon::now()->timestamp . '.' . $counter++ . '.' . $extension;
                    $this->generateProductGalleryThumbnail($file, $filename);
                    $gallery_images[] = $filename;
                }
            }
        }

        $product->images = implode(',', $gallery_images);
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }

    public function generateProductGalleryThumbnail($image, $imageName)
    {
        $thumbnailDestinationFilePath = public_path('uploads/products/thumbnails');
        $destinationFilePath = public_path('uploads/products');
        $image->move($destinationFilePath, $imageName);
        $image = Image::read($destinationFilePath.'/'.$imageName);
        $image->cover(540, 689, 'top');
        $image->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationFilePath.'/'.$imageName);

        $image->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailDestinationFilePath.'/'.$imageName);
    }

    public function product_delete($id)
    {
        $product = Product::findOrFail($id);

        // Delete gallery images
        if ($product->images) {
            $galleryImages = explode(",", $product->images);
            foreach ($galleryImages as $galleryImage) {
                if (file_exists(public_path('uploads/products/thumbnails' . $galleryImage))) {
                    unlink(public_path('uploads/products/thumbnails' . $galleryImage));
                }
            }
        }

        // Delete product image
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }

        // Delete product
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product has been deleted successfully!');
    }

}
