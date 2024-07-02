<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Products::get()]);
    }

     public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

        // image upload
        $imageName = time(). '.' .$request->image->extension();
        $request->image->move(public_path('products'), $imageName);

        $products = new Products();
        $products->image = $imageName;
        $products->name= $request->name;
        $products->description = $request->description;
        $products->save();
        return back()->withSuccess('Product created!!');
    }

    public function edit($id)
    {
        $product = Products::findOrFail($id);
        return view('products.edit', ['products' => $product]);
    }

    public function update(Request $request,$id)
    {
        $products = Products::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

       if(isset($request->image))
       {
         // image upload
        $imageName = time(). '.' .$request->image->extension();
        $request->image->move(public_path('products'), $imageName);
        $products->image = $imageName;
       }

        $products->name= $request->name;
        $products->description = $request->description;
        $products->save();
        return back()->withSuccess('Product updated!!');
    }

    public function destory(Request $request,$id)
    {
        $products = Products::findOrFail($id);
        $products->delete();
        return back()->withSuccess('Product deleted!!');
    }
}