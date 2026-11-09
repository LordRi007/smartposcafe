<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;

use App\Models\Receipt;
class ProductController extends Controller
{
   
public function invoices()
{
    $receipts = Receipt::with('items')
     ->orderby('id','desc')
    ->get();
    return view('Invoices', compact('receipts'));
}


       public function Showproducts()
    {
        $d['pro']=DB::table('products')->where('status',1)
        ->orderby('id','desc')
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

    // Use original filename with unique suffix to avoid name conflicts
   $imgName = time() . '.' . $request->img->extension();
    $request->img->move(public_path('img'), $imgName);

   

    // Save product
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
public function Count() {
        $ordercount  = Receipt::with('items')
                                        ->count();
        
        return view('welcome')->with('ordercount', $ordercount);
    }
   

// public function index()
// {
//     $usercount = DB::table('users')->where('status',1)->count();
//     $total=DB::table('receipts')->sum('total');
//     $co = DB::table('receipts')->count();
//     $pro = DB::table('products')->where('status',1)->count();

  
//     // Generate month labels and fill data
//     $labels = [];
//     $data = [];

//     for ($i = 1; $i <= 12; $i++) {
//         $labels[] = date('M', mktime(0, 0, 0, $i, 10)); // Jan, Feb, etc.
//         $data[] = $monthlySales[$i] ?? 0; // Default to 0 if no data
//     }

//     return view('welcome', compact('usercount', 'total', 'co', 'pro', 'labels', 'data'));
// }



public function index()
{
   $usercount = DB::table('users')->where('status',1)->count();
    $total=DB::table('receipts')->sum('total');
    $co = DB::table('receipts')->count();
    $pro = DB::table('products')->where('status',1)->count();

    // Group sales by date
    $dailySales = DB::table('receipts')
        ->selectRaw('DATE(created_at) as date, SUM(total) as total')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->limit(10) // latest 10 days
        ->get();

    $labels = $dailySales->pluck('date')->toArray();
    $data = $dailySales->pluck('total')->toArray();

    return view('welcome', compact('usercount', 'total', 'co', 'pro', 'labels', 'data'));
}

public function salesCalendar()
{
    $sales = DB::table('receipts')
        ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(id) as count')
        ->groupBy('date')
        ->get()
        ->keyBy('date');
        
    $dailyTarget = DB::table('settings')->where('key', 'daily_sales_target')->value('value') ?? '500.00';
        
    return view('Calendar', compact('sales', 'dailyTarget'));
}

public function updateSetting(Request $request)
{
    $request->validate([
        'key' => 'required|string',
        'value' => 'required|numeric|min:0',
    ]);
    
    DB::table('settings')->updateOrInsert(
        ['key' => $request->key],
        ['value' => $request->value, 'updated_at' => now()]
    );
    
    return redirect()->back()->with('success', 'Setting updated successfully.');
}
}
