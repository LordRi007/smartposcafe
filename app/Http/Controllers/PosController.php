<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        
        $todaySales = DB::table('receipts')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->sum('total');

        $dailyTarget = DB::table('settings')->where('key', 'daily_sales_target')->value('value') ?? 500.00;

        return view('page.Pos', compact('products', 'todaySales', 'dailyTarget'));
    }

    
public function checkout(Request $request)
{
    $data = $request->validate([
        'cart' => 'required|array',
        'received' => 'required|numeric',
        'total' => 'required|numeric',
        'change' => 'required|numeric',
        'payment_method' => 'nullable|string',
    ]);

    // Create the receipt
    $receipt = \App\Models\Receipt::create([
        'total' => $data['total'],
        'received' => $data['received'],
        'change' => $data['change'],
        'payment_method' => $data['payment_method'] ?? 'Cash',
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

public function generateQR(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'currency' => 'required|string|in:USD,KHR',
        'billNumber' => 'nullable|string',
    ]);

    $billNumber = $validated['billNumber'] ?? ('INV-' . time());

    try {
        $response = Http::withHeaders([
            'x-api-key' => 'khqr-202b72d1-52c6-45f2-9411-2743016c9606',
            'Content-Type' => 'application/json',
        ])->post('https://api.2008.run/api/generate-qr', [
            'amount' => (float) $validated['amount'],
            'currency' => $validated['currency'],
            'billNumber' => $billNumber,
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate QR code from KHQR provider.',
            'details' => $response->body()
        ], 522);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An exception occurred while generating QR.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function checkPayment(Request $request)
{
    $validated = $request->validate([
        'md5' => 'required|string',
    ]);

    try {
        $response = Http::withHeaders([
            'x-api-key' => 'khqr-202b72d1-52c6-45f2-9411-2743016c9606',
            'Content-Type' => 'application/json',
        ])->post('https://api.2008.run/api/check-payment', [
            'md5' => $validated['md5'],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to verify payment with KHQR provider.',
            'details' => $response->body()
        ], 522);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An exception occurred while checking payment status.',
            'error' => $e->getMessage()
        ], 500);
    }
}

 public function co() {
        $co = DB::table('receipts')->count();
        
        return view('welcome')->with('usercount', $usercount);
    }
    

   

    
}