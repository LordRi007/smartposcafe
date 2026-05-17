@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Invoice #{{ $receipt->id }}</h3>
    <p><strong>Date:</strong> {{ $receipt->created_at->format('Y-m-d H:i') }}</p>
    <p><strong>Total:</strong> ${{ number_format($receipt->total_amount, 2) }}</p>
    <p><strong>Received:</strong> ${{ number_format($receipt->received_amount, 2) }}</p>
    <p><strong>Change:</strong> ${{ number_format($receipt->change_amount, 2) }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipt->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Unknown' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="#" onclick="window.print()" class="btn btn-success">Print</a>
</div>
@endsection
