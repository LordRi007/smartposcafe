<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use App\Models\Receipt;
use App\Models\ReceiptItem;

class PosController extends Controller
{
  public function showPOS()
{
    $products = Product::where('status', 1)->get()->map(function($p) {
        return [
            'name' => $p->name,
            'price' => $p->price,
            'img' => asset('img/' . $p->img)
        ];
    })->toArray();

    return view('page.Pos', compact('products'));
}

public function checkout(Request $request)
{
    try {
        $items = $request->input('items');
        $total = $request->input('total_amount');
        $received = $request->input('received_amount');
        $change = $request->input('change_amount');

        // Validate input
        if (!$items || !$total || !$received) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        // Save order (adjust table/model names to yours)
        $order = new Order();
        $order->total_amount = $total;
        $order->received_amount = $received;
        $order->change_amount = $change;
        $order->save();

        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}

public function storeReceipt(Request $request)
{
    $data = $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'received_amount' => 'required|numeric|min:0',
        'change_amount' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($data) {
        $receipt = Receipt::create([
            'total_amount' => $data['total_amount'],
            'received_amount' => $data['received_amount'],
            'change_amount' => $data['change_amount'],
            'cashier_id' => auth()->id(), // optional
        ]);

        foreach ($data['items'] as $item) {
            ReceiptItem::create([
                'receipt_id' => $receipt->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }
    });

    return response()->json(['message' => 'Receipt saved successfully']);
}


}