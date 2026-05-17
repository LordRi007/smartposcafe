@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoices</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Total</th>
                <th>Received</th>
                <th>Change</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipts as $receipt)
            <tr>
                <td>{{ $receipt->id }}</td>
                <td>${{ number_format($receipt->total_amount, 2) }}</td>
                <td>${{ number_format($receipt->received_amount, 2) }}</td>
                <td>${{ number_format($receipt->change_amount, 2) }}</td>
                <td>{{ $receipt->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('invoices.detail', $receipt->id) }}" class="btn btn-sm btn-primary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $receipts->links() }}
</div>
@endsection
