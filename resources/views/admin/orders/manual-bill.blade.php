@php
    $billing = is_array($order->billing_address) ? $order->billing_address : [];
    $companyName = $settings->site_name ?: 'S K SURGICAL';
    $companyAddress = $settings->address ?: 'SAKETVIHAR MAGADH VIKASH COLONY Phulwari sharif Patna, BIHAR- 801505';
    $companyEmail = $settings->email ?: 'amitk75541@gmail.com';
    $companyPhone = $settings->phone ?: '620051348, 8789626518';
    $companyGstin = '10QUHPS7501F1ZI';
    $customerView = $customerView ?? false;
    $invoiceNo = preg_replace('/\D+/', '', $order->invoice_number ?: $order->order_number) ?: ($order->id + 1000);
    $paymentMode = strtoupper(str_replace('_', ' ', $order->payment_method ?: 'cash'));
    $itemCount = max($order->items->sum('qty'), 0);
    $grossTotal = $order->items->sum(fn ($item) => $item->qty * $item->unit_price);
    $discountPercent = $grossTotal > 0 ? round(($order->discount_amount / $grossTotal) * 100, 2) : 0;
    $roundOff = round(round($order->total) - $order->total, 2);
    $displayTotal = round($order->total);

    $formatMoney = function ($amount, $decimals = 2) {
        $amount = (float) $amount;
        $negative = $amount < 0 ? '-' : '';
        $amount = abs($amount);
        $parts = explode('.', number_format($amount, $decimals, '.', ''));
        $lastThree = substr($parts[0], -3);
        $rest = substr($parts[0], 0, -3);
        if ($rest !== '') {
            $lastThree = ',' . $lastThree;
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        }
        return $negative . $rest . $lastThree . ($decimals > 0 ? '.' . $parts[1] : '');
    };

    $ones = ['', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN', 'ELEVEN', 'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'];
    $tens = ['', '', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'];
    $twoDigits = function ($num) use (&$ones, &$tens) {
        $num = (int) $num;
        if ($num < 20) return $ones[$num];
        return trim($tens[intdiv($num, 10)] . ' ' . $ones[$num % 10]);
    };
    $threeDigits = function ($num) use (&$ones, &$twoDigits) {
        $num = (int) $num;
        return trim((intdiv($num, 100) ? $ones[intdiv($num, 100)] . ' HUNDRED ' : '') . (($num % 100) ? $twoDigits($num % 100) : ''));
    };
    $amountWords = function ($amount) use (&$threeDigits) {
        $num = (int) round($amount);
        if ($num === 0) return 'ZERO ONLY';
        $parts = [];
        $crore = intdiv($num, 10000000); $num %= 10000000;
        $lakh = intdiv($num, 100000); $num %= 100000;
        $thousand = intdiv($num, 1000); $num %= 1000;
        if ($crore) $parts[] = $threeDigits($crore) . ' CRORE';
        if ($lakh) $parts[] = $threeDigits($lakh) . ' LAKH';
        if ($thousand) $parts[] = $threeDigits($thousand) . ' THOUSAND';
        if ($num) $parts[] = $threeDigits($num);
        return trim(implode(' ', $parts)) . ' ONLY';
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill {{ $invoiceNo }}</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#333;color:#000;font-family:Arial,"Helvetica Neue",sans-serif}
        .toolbar{width:760px;margin:16px auto;display:flex;justify-content:space-between;gap:10px}
        .toolbar a,.toolbar button{border:1px solid #cbd5e1;background:#fff;color:#0f172a;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:700;text-decoration:none;cursor:pointer}
        .toolbar button{background:#111827;color:#fff;border-color:#111827}
        .sheet{width:760px;min-height:1060px;margin:0 auto 24px;background:#fff;padding:22px 48px 62px}
        .invoice{border:2px solid #000;width:100%;position:relative}
        .watermark{position:absolute;top:390px;left:92px;width:410px;height:70px;border:1px solid #999;transform:rotate(-18deg);display:flex;align-items:center;justify-content:center;color:rgba(0,0,0,.18);font-size:48px;font-weight:900;letter-spacing:2px;pointer-events:none}
        table{width:100%;border-collapse:collapse}
        td,th{border:2px solid #000;padding:2px 3px;font-size:12px;line-height:1.2}
        .no-border{border:0!important}
        .top-left{width:46%;vertical-align:top;border-left:0;border-top:0;border-bottom:0;padding:0}
        .top-right{width:54%;vertical-align:top;border-right:0;border-top:0;border-bottom:0;padding:0}
        .company{padding:2px 3px 0}
        .company h1{font-size:18px;line-height:1;margin:0 0 5px;font-weight:900}
        .company p{font-size:12px;line-height:1.35;margin:0 0 3px}
        .meta td{height:20px;font-size:12px}
        .meta .label{text-align:center;font-weight:800}
        .meta .value{font-weight:900;text-align:center}
        .customer-title{text-align:center;font-weight:400}
        .items th{font-size:11px;font-weight:900;text-align:center}
        .items td{height:20px;text-align:center}
        .items .desc{text-align:center;font-weight:700}
        .items .blank{color:transparent}
        .summary td{height:22px}
        .tax td{height:22px}
        .grand-title{text-align:center;font-size:18px;font-weight:900;border:0!important}
        .grand-value{text-align:right;font-size:13px;font-weight:900;border:0!important}
        .words{border-top:0;border-left:0;border-right:0;border-bottom:0;padding:4px 2px;font-size:12px;height:48px;vertical-align:top}
        .footer td{height:84px;vertical-align:top}
        .declaration{width:46%;border-left:0;border-bottom:0;padding:5px 2px}
        .signature{width:54%;border-right:0;border-bottom:0;padding:0}
        .signature-inner{height:84px;border-left:2px solid #000;display:grid;grid-template-rows:1fr 24px}
        .signature-top{text-align:right;padding:6px 5px 0;font-size:12px}
        .signature-bottom{display:grid;grid-template-columns:1fr 1fr 1.4fr;text-align:center;align-items:end;font-size:12px}
        .num{text-align:right!important}
        .center{text-align:center!important}
        .bold{font-weight:900}
        @media print{
            body{background:#fff}
            .toolbar{display:none}
            .sheet{width:auto;min-height:auto;margin:0;padding:0}
            .invoice{border-width:2px}
            @page{size:A4;margin:9mm}
        }
    </style>
</head>
<body>
    <div class="toolbar">
        @if($customerView)
            <a href="{{ route('orders.show', $order->order_number) }}">Back to Order</a>
            <div style="display:flex;gap:10px">
                <a href="{{ route('user.dashboard') }}">My Dashboard</a>
                <button type="button" onclick="window.print()">Download / Save PDF</button>
            </div>
        @else
            <a href="{{ route('admin.orders.index') }}">Back to Orders</a>
            <div style="display:flex;gap:10px">
                <a href="{{ route('admin.orders.manualBilling') }}">New Manual Bill</a>
                <button type="button" onclick="window.print()">Print / Save PDF</button>
            </div>
        @endif
    </div>

    <main class="sheet">
        <section class="invoice">
            <div class="watermark">{{ $companyName }}</div>

            <table>
                <tr>
                    <td class="top-left">
                        <div class="company">
                            <h1>{{ $companyName }}</h1>
                            <p>{{ $companyAddress }}</p>
                            <p>GSTIN:- {{ $companyGstin }}</p>
                            <p>STATE NAME: BIHAR, CODE 10</p>
                            <br>
                            <p>E-Mail - {{ $companyEmail }}</p>
                            <p>MOB - {{ $companyPhone }}</p>
                        </div>
                    </td>
                    <td class="top-right">
                        <table class="meta">
                            <tr>
                                <td class="label">Invoice No.</td>
                                <td class="label" colspan="2">DATE-{{ optional($order->invoice_date ?? $order->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="value">{{ $invoiceNo }}</td>
                                <td class="label">Mode -</td>
                                <td class="value">{{ $paymentMode }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="customer-title">Customer Detail</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="bold">M/S</td>
                                <td colspan="2">{{ $billing['name'] ?? ($order->user->name ?? 'Walk-in Customer') }}</td>
                            </tr>
                            <tr>
                                <td class="bold">Address</td>
                                <td colspan="2">{{ $billing['address_1'] ?? $billing['address'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bold">GSTIN</td>
                                <td colspan="2">{{ $order->buyer_gst_no ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bold">Phone</td>
                                <td colspan="2">{{ $billing['phone'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="items">
                <thead>
                    <tr>
                        <th style="width:28px">Sr.</th>
                        <th>Description of Goods</th>
                        <th style="width:68px">Batch</th>
                        <th style="width:50px">HSN Code</th>
                        <th style="width:46px">Exp</th>
                        <th style="width:42px">MRP</th>
                        <th style="width:36px">Qty</th>
                        <th style="width:48px">Disc(%)</th>
                        <th style="width:70px">TOTAL</th>
                        <th style="width:74px">Taxable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="desc">{{ $item->product_name }}{{ $item->variant_name ? ' - ' . $item->variant_name : '' }}</td>
                            <td>{{ $item->batch_number ?: '-' }}</td>
                            <td>{{ $item->hsn_code ?: '-' }}</td>
                            <td>{{ $item->expiry_date ? $item->expiry_date->format('m/Y') : '-' }}</td>
                            <td>{{ $formatMoney($item->mrp ?? 0, 0) }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $formatMoney($item->discount_percent ?? 0, 0) }}%</td>
                            <td class="num">{{ $formatMoney($item->qty * $item->unit_price, 2) }}</td>
                            <td class="num">{{ $formatMoney($item->taxable_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    @for($i = $order->items->count() + 1; $i <= 20; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td class="blank">&nbsp;</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <table class="summary">
                <tr>
                    <td class="bold center" colspan="6">Total</td>
                    <td class="center" style="width:36px">{{ $itemCount }}</td>
                    <td class="center" style="width:48px">{{ $formatMoney($discountPercent, 0) }}%</td>
                    <td class="num" style="width:70px">{{ $formatMoney($grossTotal, 2) }}</td>
                    <td class="num" style="width:74px">{{ $formatMoney($order->subtotal, 2) }}</td>
                </tr>
            </table>

            <table class="tax">
                <tr>
                    <td rowspan="4" style="border-left:0;width:46%"></td>
                    @if($order->igst > 0)
                        <td class="bold" style="width:42px">IGST</td>
                        <td class="center" style="width:48px">{{ $order->items->max('gst_rate') }}%</td>
                        <td class="num" style="width:86px">{{ $formatMoney($order->igst, 3) }}</td>
                    @else
                        <td class="bold" style="width:42px">CGST</td>
                        <td class="center" style="width:48px">{{ $formatMoney(($order->items->max('gst_rate') ?? 0) / 2, 2) }}%</td>
                        <td class="num" style="width:86px">{{ $formatMoney($order->cgst, 3) }}</td>
                    @endif
                    <td rowspan="4" class="num" style="border-right:0;vertical-align:bottom;font-weight:700">₹ {{ $formatMoney($order->total, 2) }}</td>
                </tr>
                <tr>
                    @if($order->igst > 0)
                        <td class="bold">Round off</td>
                        <td></td>
                        <td class="num">{{ $formatMoney($roundOff, 2) }}</td>
                    @else
                        <td class="bold">SGST</td>
                        <td class="center">{{ $formatMoney(($order->items->max('gst_rate') ?? 0) / 2, 2) }}%</td>
                        <td class="num">{{ $formatMoney($order->sgst, 3) }}</td>
                    @endif
                </tr>
                <tr>
                    @if($order->igst > 0)
                        <td></td><td></td><td></td>
                    @else
                        <td class="bold">Round off</td>
                        <td></td>
                        <td class="num">{{ $formatMoney($roundOff, 2) }}</td>
                    @endif
                </tr>
                <tr>
                    <td class="grand-title" colspan="3">TOTAL</td>
                </tr>
                <tr>
                    <td class="grand-title" style="border-left:0;border-top:0" colspan="4">TOTAL</td>
                    <td class="grand-value" style="border-right:0;border-top:0">₹ {{ $formatMoney($displayTotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="words" colspan="5">
                        Tax Amount&nbsp; ( in words) :- <span class="bold">{{ $amountWords($displayTotal) }}.</span>
                    </td>
                </tr>
            </table>

            <table class="footer">
                <tr>
                    <td class="declaration">
                        <strong><u>Declaration</u></strong><br>
                        <strong>We declare that this invoice shows the actual price<br>
                        of the goods described and that all particulars are<br>
                        true and correct.</strong>
                    </td>
                    <td class="signature">
                        <div class="signature-inner">
                            <div class="signature-top">For {{ $companyName }}</div>
                            <div class="signature-bottom">
                                <span>Prepared by</span>
                                <span>Verified by</span>
                                <span>Authorised signatory</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </section>
    </main>
    @if($customerView && request()->boolean('download'))
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    window.print();
                }, 300);
            });
        </script>
    @endif
</body>
</html>
