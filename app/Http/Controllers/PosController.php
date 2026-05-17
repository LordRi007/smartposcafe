<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function showPOS()
    {   
        $products = Product::where('status', 1)->orderBy('id','desc')->get()->map(function($product) {
            return [
                'id' => $product->id,
                'cat_id'=>$product->cat_id,
                'name' => $product->name,
                'price' => $product->price,
                'img' => asset('img/' . $product->img)
            ];
        })->toArray();
        

        return view('page.Pos', compact('products'));
    }

    
public function checkout(Request $request)
{
    $data = $request->validate([
        'cart' => 'required|array',
        'received' => 'required|numeric',
        'total' => 'required|numeric',
        'change' => 'required|numeric',
    ]);

    // Create the receipt
    $receipt = \App\Models\Receipt::create([
        'total' => $data['total'],
        'received' => $data['received'],
        'change' => $data['change'],
    ]);

    // Create receipt items
    foreach ($data['cart'] as $item) {
        $receipt->items()->create([
            'product_name' => $item['name'],
            'quantity' => $item['qty'],
            'price' => $item['price'],
        ]);
    }

    return response()->json(['success' => true]);
}
 public function co() {
        $co = DB::table('receipts')->count();
        
        return view('welcome')->with('usercount', $usercount);
    }
    

   

    
}