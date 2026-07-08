<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; font-size: 13px; margin: 0; padding: 32px; }
        .header { display: table; width: 100%; margin-bottom: 28px; }
        .header .col { display: table-cell; vertical-align: top; }
        .brand { font-size: 22px; font-weight: bold; color: #2563EB; }
        .muted { color: #6B7280; }
        .text-right { text-align: right; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: bold; }
        .badge-paid { background: #DCFCE7; color: #15803D; }
        .badge-partial { background: #FEF3C7; color: #B45309; }
        .badge-due { background: #FEE2E2; color: #B91C1C; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background: #F1F5F9; text-align: left; padding: 10px; font-size: 11px; text-transform: uppercase; color: #6B7280; }
        table.items td { padding: 10px; border-bottom: 1px solid #E5E7EB; font-size: 12px; }
        .totals { width: 280px; margin-left: auto; margin-top: 20px; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { padding: 6px 0; font-size: 13px; }
        .totals .grand td { border-top: 2px solid #111827; font-weight: bold; font-size: 16px; padding-top: 10px; color: #2563EB; }
        .footer { margin-top: 50px; text-align: center; color: #6B7280; font-size: 11px; border-top: 1px solid #E5E7EB; padding-top: 16px; }
        .box { background: #F8FAFC; border-radius: 8px; padding: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="col">
            <div class="brand">{{ \App\Models\Setting::get('company_name', config('app.name', 'Billing System')) }}</div>
            <div class="muted">{{ \App\Models\Setting::get('company_address', '') }}</div>
            <div class="muted">{{ \App\Models\Setting::get('company_email', '') }} {{ \App\Models\Setting::get('company_phone', '') }}</div>
        </div>
        <div class="col text-right">
            <div style="font-size:20px; font-weight:bold;">INVOICE</div>
            <div class="muted">#{{ $invoice->invoice_no }}</div>
            <div class="muted">Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
            <div style="margin-top:8px;">
                <span class="badge badge-{{ $invoice->payment_status }}">{{ strtoupper($invoice->payment_status) }}</span>
            </div>
        </div>
    </div>

    <div class="box">
        <strong>Bill To:</strong><br>
        {{ $invoice->customer->name ?? 'Walk-in Customer' }}<br>
        @if ($invoice->customer)
            {{ $invoice->customer->address }}<br>
            {{ $invoice->customer->email }} {{ $invoice->customer->phone }}
        @endif
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Tax</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '—' }}</td>
                    <td>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>₹{{ number_format($item->discount, 2) }}</td>
                    <td>₹{{ number_format($item->tax, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td>Subtotal</td><td class="text-right">₹{{ number_format($invoice->subtotal, 2) }}</td></tr>
            <tr><td>Discount</td><td class="text-right">₹{{ number_format($invoice->discount, 2) }}</td></tr>
            <tr><td>Tax</td><td class="text-right">₹{{ number_format($invoice->tax, 2) }}</td></tr>
            <tr class="grand"><td>Total</td><td class="text-right">₹{{ number_format($invoice->total, 2) }}</td></tr>
            <tr><td>Paid</td><td class="text-right">₹{{ number_format($invoice->paid_amount, 2) }}</td></tr>
            <tr><td>Due</td><td class="text-right">₹{{ number_format($invoice->due_amount, 2) }}</td></tr>
        </table>
    </div>

    @if ($invoice->note)
        <div style="margin-top:24px;"><strong>Note:</strong> {{ $invoice->note }}</div>
    @endif

    <div style="margin-top:60px; display:table; width:100%;">
        <div style="display:table-cell; width:50%;">&nbsp;</div>
        <div style="display:table-cell; width:50%; text-align:center; border-top:1px solid #111827; padding-top:6px;">Authorized Signature</div>
    </div>

    <div class="footer">Thank you for your business! · Generated by {{ config('app.name', 'Billing System') }}</div>
</body>
</html>
