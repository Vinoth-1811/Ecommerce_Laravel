<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{

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
}
