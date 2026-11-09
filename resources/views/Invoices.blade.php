@extends('layouts.app')

@section('title', 'SmartCafe - Invoices')
@section('page_title', 'Invoice History')

@section('styles')
<style>
    /* Printing stylesheet fallback */
    @media print {
        body {
            visibility: hidden !important;
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        #receiptModal {
            position: fixed !important;
            inset: 0 !important;
            background: #fff !important;
            visibility: visible !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }
        #receiptModal > div {
            transform: none !important;
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            max-width: 80mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        #receipt-print-area, #receipt-print-area * {
            visibility: visible !important;
            color: #000 !important;
            background: transparent !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h3 class="text-lg font-bold text-slate-950 dark:text-white">Transaction Logs</h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Browse, review, and print receipts for cafe transactions</p>
    </div>
</div>

<!-- Invoices Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 font-semibold">
                    <th class="px-6 py-4.5 font-bold">Receipt ID</th>
                    <th class="px-6 py-4.5 font-bold">Total (USD)</th>
                    <th class="px-6 py-4.5 font-bold">Payment Method</th>
                    <th class="px-6 py-4.5 font-bold">Received (USD)</th>
                    <th class="px-6 py-4.5 font-bold">Change (USD)</th>
                    <th class="px-6 py-4.5 font-bold">Date & Time</th>
                    <th class="px-6 py-4.5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach ($receipts as $receipt)
                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/10 transition-colors duration-150">
                    <!-- ID -->
                    <td class="px-6 py-4.5 font-semibold text-slate-900 dark:text-white">
                        #{{ $receipt->id }}
                    </td>
                    <!-- Total -->
                    <td class="px-6 py-4.5 font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($receipt->total, 2) }}
                    </td>
                    <!-- Payment Method -->
                    <td class="px-6 py-4.5">
                        @if(strtolower($receipt->payment_method ?? 'cash') === 'khqr')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-55/60 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 text-xs font-semibold rounded-full border border-indigo-100 dark:border-indigo-950/30">
                                <i class="fas fa-qrcode text-[10px]"></i>
                                KHQR
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-55/65 dark:bg-slate-955/20 text-slate-700 dark:text-slate-400 text-xs font-semibold rounded-full border border-slate-100 dark:border-slate-950/30">
                                <i class="fas fa-money-bill-wave text-[10px]"></i>
                                Cash
                            </span>
                        @endif
                    </td>
                    <!-- Received -->
                    <td class="px-6 py-4.5 text-slate-600 dark:text-slate-400 font-medium">
                        ${{ number_format($receipt->received, 2) }}
                    </td>
                    <!-- Change -->
                    <td class="px-6 py-4.5 text-slate-600 dark:text-slate-400 font-medium">
                        ${{ number_format($receipt->change, 2) }}
                    </td>
                    <!-- Date -->
                    <td class="px-6 py-4.5 text-slate-400 dark:text-slate-500 font-medium">
                        {{ $receipt->created_at->format('Y-m-d H:i') }}
                    </td>
                    <!-- Actions -->
                    <td class="px-6 py-4.5 text-right">
                        <button 
                            onclick='openReceiptModal(@json($receipt))' 
                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 text-slate-700 dark:text-slate-355 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold rounded-xl transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer"
                        >
                            <i class="fas fa-eye text-xs"></i>
                            <span>View Details</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Receipt Details -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-md hidden transition-opacity duration-300" id="receiptModal">
    <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300">
        <!-- Close & Action bar (Non printable) -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/50 dark:border-slate-800/50 no-print">
            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Receipt Details</span>
            <button onclick="closeReceiptModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-times"></i></button>
        </div>

        <!-- Receipt Print Area (Printed content) -->
        <div class="p-6 md:p-8 overflow-y-auto max-h-[70vh] bg-white dark:bg-slate-900" id="receipt-print-area">
            
            <!-- Cafe Info -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-black tracking-wider text-slate-900 dark:text-white uppercase font-sans">SmartCafe</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase mt-1">Cashier Checkout Slip</p>
                <div class="border-b border-dashed border-slate-200 dark:border-slate-800 my-4"></div>
                <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
                    <span>Receipt: <strong id="receiptId" class="text-slate-700 dark:text-slate-300 font-bold">#0</strong></span>
                    <span id="receiptDate">0000-00-00 00:00</span>
                </div>
                <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400 mt-1">
                    <span>Payment: <strong id="receiptMethod" class="text-slate-700 dark:text-slate-300 font-bold">Cash</strong></span>
                </div>
            </div>

            <!-- Items list -->
            <div id="receiptItems" class="space-y-3 mb-6">
                <!-- Rendered dynamically -->
            </div>

            <div class="border-b border-dashed border-slate-200 dark:border-slate-800 mb-6"></div>

            <!-- Summary Table -->
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between font-semibold text-slate-600 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span>$<span id="receiptSubtotal">0.00</span></span>
                </div>
                <div class="flex justify-between font-bold text-slate-950 dark:text-white text-base">
                    <span>Total USD</span>
                    <span>$<span id="receiptTotal">0.00</span></span>
                </div>
                <div class="flex justify-between font-semibold text-slate-500 dark:text-slate-400 text-xs">
                    <span>Total (KHR)</span>
                    <span>៛<span id="receiptTotalKHR">0</span></span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-800/80 my-2"></div>
                <div class="flex justify-between font-medium text-slate-600 dark:text-slate-400 text-xs">
                    <span>Received (USD)</span>
                    <span>$<span id="receiptReceived">0.00</span></span>
                </div>
                <div class="flex justify-between font-bold text-indigo-600 dark:text-indigo-400">
                    <span>Change USD</span>
                    <span>$<span id="receiptChange">0.00</span></span>
                </div>
                <div class="flex justify-between font-semibold text-indigo-500 dark:text-indigo-400 text-xs">
                    <span>Change (KHR)</span>
                    <span>៛<span id="receiptChangeKHR">0</span></span>
                </div>
            </div>

            <!-- Receipt Footer Message -->
            <div class="text-center mt-8 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                <p class="text-xs font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Thank you for your visit!</p>
                <p class="text-[10px] text-slate-300 dark:text-slate-600 mt-1">SmartPOS Cafe Operations System</p>
            </div>
        </div>

        <!-- Receipt Print Action (Non-printable) -->
        <div class="p-6 border-t border-slate-200/50 dark:border-slate-800/50 bg-slate-50 dark:bg-slate-900/60 no-print">
            <button onclick="printReceipt()" class="w-full flex items-center justify-center gap-2 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <i class="fas fa-print"></i>
                <span>Print Receipt</span>
            </button>
        </div>
    </div>
</div>

<!-- Hidden Print Iframe -->
<iframe id="receipt-print-iframe" style="position: fixed; right: 0; bottom: 0; width: 0; height: 0; border: 0; opacity: 0; pointer-events: none;"></iframe>
@endsection

@section('scripts')
<script>
    let currentReceipt = null;

    // Open Receipt details and compute currency fields
    function openReceiptModal(receipt) {
        currentReceipt = receipt;
        const rate = 4100; // Exchange rate USD -> KHR

        const totalUSD = parseFloat(receipt.total);
        const receivedUSD = parseFloat(receipt.received);
        const changeUSD = parseFloat(receipt.change);

        document.getElementById('receiptId').textContent = '#' + receipt.id;
        document.getElementById('receiptMethod').textContent = receipt.payment_method || 'Cash';
        document.getElementById('receiptTotal').textContent = totalUSD.toFixed(2);
        document.getElementById('receiptSubtotal').textContent = totalUSD.toFixed(2);
        document.getElementById('receiptReceived').textContent = receivedUSD.toFixed(2);
        document.getElementById('receiptChange').textContent = changeUSD.toFixed(2);
        
        // Format date and time
        const createdDate = new Date(receipt.created_at);
        const formattedDate = createdDate.getFullYear() + '-' + 
                              String(createdDate.getMonth() + 1).padStart(2, '0') + '-' + 
                              String(createdDate.getDate()).padStart(2, '0') + ' ' + 
                              String(createdDate.getHours()).padStart(2, '0') + ':' + 
                              String(createdDate.getMinutes()).padStart(2, '0');
        document.getElementById('receiptDate').textContent = formattedDate;

        // Dynamic KHR values
        document.getElementById('receiptTotalKHR').textContent = Math.round(totalUSD * rate).toLocaleString();
        document.getElementById('receiptChangeKHR').textContent = Math.round(changeUSD * rate).toLocaleString();

        // Render receipt item details list
        let itemsHtml = '';
        receipt.items.forEach(item => {
            const totalPrice = item.quantity * item.price;
            itemsHtml += `
                <div class="flex items-start justify-between gap-4 text-xs font-medium">
                    <div class="flex-1">
                        <span class="font-bold text-slate-800 dark:text-slate-200">${item.product_name}</span>
                        <span class="block text-slate-400 dark:text-slate-500">${item.quantity} x $${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                    <span class="text-slate-800 dark:text-slate-200 font-semibold">$${totalPrice.toFixed(2)}</span>
                </div>
            `;
        });

        document.getElementById('receiptItems').innerHTML = itemsHtml;
        
        // Modal entry animation
        const modal = document.getElementById('receiptModal');
        const content = modal.querySelector('div');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Print Receipt via hidden iframe (reliable across all browsers and thermal printers)
    function printReceipt() {
        if (!currentReceipt) return;

        const rate = 4100;
        const totalUSD = parseFloat(currentReceipt.total);
        const receivedUSD = parseFloat(currentReceipt.received || 0);
        const changeUSD = parseFloat(currentReceipt.change || 0);

        const createdDate = new Date(currentReceipt.created_at);
        const formattedDate = createdDate.getFullYear() + '-' + 
                              String(createdDate.getMonth() + 1).padStart(2, '0') + '-' + 
                              String(createdDate.getDate()).padStart(2, '0') + ' ' + 
                              String(createdDate.getHours()).padStart(2, '0') + ':' + 
                              String(createdDate.getMinutes()).padStart(2, '0');

        let itemsRows = '';
        if (currentReceipt.items && currentReceipt.items.length) {
            currentReceipt.items.forEach(item => {
                const itemTotal = item.quantity * item.price;
                itemsRows += `
                    <tr>
                        <td style="text-align: left; padding: 4px 0; vertical-align: top;">
                            <div style="font-weight: bold;">${item.product_name}</div>
                            <div style="font-size: 11px; color: #555;">${item.quantity} x $${parseFloat(item.price).toFixed(2)}</div>
                        </td>
                        <td style="text-align: right; padding: 4px 0; vertical-align: top; font-weight: bold;">
                            $${itemTotal.toFixed(2)}
                        </td>
                    </tr>
                `;
            });
        }

        const receiptHtml = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Receipt #${currentReceipt.id} - SmartCafe</title>
                <style>
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    body {
                        font-family: 'Courier New', Courier, monospace;
                        margin: 0;
                        padding: 12px 14px;
                        width: 72mm;
                        font-size: 12px;
                        color: #000;
                        background: #fff;
                        line-height: 1.35;
                    }
                    * {
                        box-sizing: border-box;
                    }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .dashed {
                        border-bottom: 1px dashed #000;
                        margin: 8px 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .summary-row {
                        display: flex;
                        justify-content: space-between;
                        padding: 2px 0;
                    }
                </style>
            </head>
            <body>
                <div class="text-center">
                    <h2 style="margin: 0; font-size: 20px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">SmartCafe</h2>
                    <p style="margin: 3px 0 0; font-size: 11px; text-transform: uppercase; color: #333;">Cashier Checkout Slip</p>
                </div>

                <div class="dashed"></div>

                <div style="font-size: 11px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Receipt: <strong>#${currentReceipt.id}</strong></span>
                        <span>${formattedDate}</span>
                    </div>
                    <div style="margin-top: 3px;">
                        <span>Payment: <strong>${currentReceipt.payment_method || 'Cash'}</strong></span>
                    </div>
                </div>

                <div class="dashed"></div>

                <table>
                    <thead>
                        <tr style="border-bottom: 1px dashed #000; font-size: 11px;">
                            <th style="text-align: left; padding-bottom: 4px;">Item</th>
                            <th style="text-align: right; padding-bottom: 4px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsRows}
                    </tbody>
                </table>

                <div class="dashed"></div>

                <div>
                    <div class="summary-row" style="font-size: 12px;">
                        <span>Subtotal:</span>
                        <span>$${totalUSD.toFixed(2)}</span>
                    </div>
                    <div class="summary-row font-bold" style="font-size: 14px; margin-top: 2px;">
                        <span>Total USD:</span>
                        <span>$${totalUSD.toFixed(2)}</span>
                    </div>
                    <div class="summary-row" style="font-size: 11px; color: #333;">
                        <span>Total (KHR):</span>
                        <span>៛${Math.round(totalUSD * rate).toLocaleString()}</span>
                    </div>
                    <div class="dashed" style="margin: 6px 0;"></div>
                    <div class="summary-row" style="font-size: 11px;">
                        <span>Received (USD):</span>
                        <span>$${receivedUSD.toFixed(2)}</span>
                    </div>
                    <div class="summary-row font-bold" style="font-size: 13px; color: #000;">
                        <span>Change USD:</span>
                        <span>$${changeUSD.toFixed(2)}</span>
                    </div>
                    <div class="summary-row" style="font-size: 11px;">
                        <span>Change (KHR):</span>
                        <span>៛${Math.round(changeUSD * rate).toLocaleString()}</span>
                    </div>
                </div>

                <div class="dashed" style="margin-top: 12px;"></div>

                <div class="text-center" style="margin-top: 10px; font-size: 11px;">
                    <p style="margin: 0; font-weight: bold; text-transform: uppercase;">Thank you for your visit!</p>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #666;">SmartPOS Cafe Operations System</p>
                </div>
            </body>
            </html>
        `;

        let iframe = document.getElementById('receipt-print-iframe');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'receipt-print-iframe';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            iframe.style.opacity = '0';
            iframe.style.pointerEvents = 'none';
            document.body.appendChild(iframe);
        }

        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(receiptHtml);
        doc.close();

        setTimeout(() => {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }, 150);
    }

    // Close Receipt modal
    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }

    // Modal background click close handler
    window.addEventListener('click', function(event) {
        const receiptModal = document.getElementById('receiptModal');
        if (event.target === receiptModal) {
            closeReceiptModal();
        }
    });

    // Modal Escape key handler
    window.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeReceiptModal();
        }
    });
</script>
@endsection
