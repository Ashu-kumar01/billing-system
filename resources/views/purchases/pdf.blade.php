<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $purchase->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; margin: 0; padding: 30px; }
        .header { width: 100%; overflow: hidden; margin-bottom: 20px; }
        .header .left { float: left; width: 50%; }
        .header .right { float: right; width: 50%; text-align: right; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th, table.items td { border: 1px solid #d1d5db; padding: 6px 8px; font-size: 11px; }
        table.items th { background: #f3f4f6; text-align: left; }
        .text-right { text-align: right; }
        .totals { width: 260px; float: right; }
        .totals table { margin-bottom: 0; }
        .totals td { padding: 4px 8px; border: none; }
        .totals tr.total td { font-weight: bold; border-top: 1px solid #d1d5db; font-size: 13px; }
        .clear { clear: both; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .section-title { font-size: 13px; font-weight: bold; margin: 16px 0 8px; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="left">
            <h1>Purchase Order</h1>
            <p class="muted">{{ $purchase->store->name ?? config('app.name') }}</p>
        </div>
        <div class="right">
            <p><strong>PO #:</strong> {{ $purchase->invoice_no }}</p>
            <p><strong>Date:</strong> {{ $purchase->purchase_date->format('d M Y') }}</p>
            <p>
                <span class="badge badge-{{ $purchase->status }}">{{ ucfirst($purchase->status) }}</span>
            </p>
        </div>
    </div>
    <div class="clear"></div>

    <p class="section-title">Supplier</p>
    <p>
        {{ $purchase->supplier->name ?? '—' }}<br>
        @if ($purchase->supplier?->company_name)
            {{ $purchase->supplier->company_name }}<br>
        @endif
    </p>

    <p class="section-title">Line Items</p>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Cost</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Deleted product' }} @if($item->product?->sku) ({{ $item->product->sku }}) @endif</td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                    <td class="text-right">{{ number_format($item->unit_cost, 2) }}</td>
                    <td class="text-right">{{ number_format($item->discount, 2) }}</td>
                    <td class="text-right">{{ number_format($item->tax, 2) }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td>Subtotal</td><td class="text-right">{{ number_format($purchase->subtotal, 2) }}</td></tr>
            <tr><td>Discount</td><td class="text-right">- {{ number_format($purchase->discount, 2) }}</td></tr>
            <tr><td>Tax</td><td class="text-right">+ {{ number_format($purchase->tax, 2) }}</td></tr>
            <tr class="total"><td>Total</td><td class="text-right">{{ number_format($purchase->total, 2) }}</td></tr>
            <tr><td>Paid</td><td class="text-right">{{ number_format($purchase->paid_amount, 2) }}</td></tr>
            <tr><td>Due</td><td class="text-right">{{ number_format($purchase->due_amount, 2) }}</td></tr>
        </table>
    </div>
    <div class="clear"></div>

    @if ($purchase->note)
        <p class="section-title">Note</p>
        <p>{{ $purchase->note }}</p>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
