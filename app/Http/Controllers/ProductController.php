<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(product::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'product_name' => 'required|max:255',
            'product_desc' => 'required|max:255',
            'product_price' => 'required|numeric',
            'product_category' => 'required|max:255',
            'product_image' => 'required',
        ]);

        if ($request->hasFile('product_image'))  
        {
            $destination_path = 'public/product_image';
            $image = $request->file('product_image');
            $image_name = time().'_'.$image->getClientOriginalName();
            $path = $request->file('product_image')->storeAs($destination_path,$image_name);

            $validateData['product_image'] = $image_name;
        }      
        $product = Product::create($validateData);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $product = Product::find($id);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validateData = $request->validate([
            'product_name' => 'required|max:255',
            'product_desc' => 'required|max:255',
            'product_price' => 'required|numeric',
            'product_category' => 'required|max:255',
            'product_image' => 'required',
        ]);

        if ($product) {
            $product->update($validateData);
            return response()->json($product, 200);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product) {
            $product->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
