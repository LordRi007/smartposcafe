<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;
class ProductController extends Controller
{
       public function Showproducts()
    {
        $d['pro']=DB::table('products')->where('status',1)
        ->orderby('id','asc')
        ->get();
        return view('Product', $d);
    }
    public function Add(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'cat_id' => 'required|string',
        'price' => 'required|numeric',
        'img' => 'required|image',
    ]);

    $imgName = time() . '.' . $request->img->extension();
    $request->img->move(public_path('img'), $imgName);

    Product::create([
        'name' => $request->name,
        'cat_id' => $request->cat_id,
        'price' => $request->price,
        'img' => $imgName,
        'status' => 1
    ]);

    return redirect('/Product')->with('success', 'Product added successfully!');
}
public function update(Request $request, $id)
{
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'cat_id' => 'required|string',
        'price' => 'required|numeric|min:0',
        'img' => 'nullable|image',
    ]);

    // Find the product
    $product = Product::findOrFail($id);

    // Update fields
    $product->name = $validated['name'];
    $product->cat_id = $validated['cat_id'];
    $product->price = $validated['price'];

    // Handle image update if new image is uploaded
    if ($request->hasFile('img')) {
        $image = $request->file('img');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('img'), $imageName);

        // Delete old image if exists
        if ($product->img && file_exists(public_path('img/' . $product->img))) {
            unlink(public_path('img/' . $product->img));
        }

        $product->img = $imageName;
    }

    // Save updates
    $product->save();

    return redirect('/Product')->with('success', 'Product updated successfully!');
}
public function delete($id)
{
    $product = Product::findOrFail($id);

    // Delete the image file if it exists
    if ($product->img && file_exists(public_path('img/' . $product->img))) {
        unlink(public_path('img/' . $product->img));
    }

    // Delete the product record from the database
    $product->update(['status' => 0]);

    return redirect('/Product')->with('success', 'Product deleted successfully!');
}

}
