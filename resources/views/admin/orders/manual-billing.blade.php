@extends('layouts.admin')
@section('page-title', 'Manual Billing')

@php
    $variantOptions = $variants->map(function ($variant) {
        return [
            'id' => $variant->id,
            'label' => trim(($variant->sku ? $variant->sku . ' - ' : '') . $variant->product->name . ' (' . $variant->name . ')'),
            'batch' => $variant->batch_number,
            'expiry' => optional($variant->expiry_date)->format('Y-m'),
            'mrp' => (float) ($variant->mrp ?? $variant->price),
            'price' => (float) $variant->price,
            'gst' => (float) $variant->effective_gst_rate,
            'stock' => (int) $variant->stock,
        ];
    })->values();
@endphp

@section('styles')
<style>
.billing-shell{display:grid;grid-template-columns:1.1fr .9fr;gap:18px;align-items:start}
.panel{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden}
.panel-head{padding:15px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:10px}
.panel-body{padding:18px}
.field{margin-bottom:14px}
.label{display:block;font-size:12px;font-weight:800;color:#334155;margin-bottom:6px}
.input,.select,.textarea{width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:9px;background:#fff;color:#0f172a;font-size:13px;outline:none}
.textarea{min-height:78px;resize:vertical}
.input:focus,.select:focus,.textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 14%,transparent)}
.grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
.btn-primary,.btn-soft,.btn-danger{display:inline-flex;align-items:center;justify-content:center;gap:7px;border-radius:9px;padding:9px 14px;font-size:13px;font-weight:800;border:0;cursor:pointer;text-decoration:none}
.btn-primary{background:var(--accent);color:#fff}
.btn-soft{background:#f8fafc;color:#334155;border:1px solid #e2e8f0}
.btn-danger{background:#fff1f2;color:#be123c;border:1px solid #fecdd3}
.items-table{width:100%;border-collapse:collapse}
.items-table th{font-size:11px;text-transform:uppercase;color:#64748b;background:#f8fafc;padding:10px;border-bottom:1px solid #e2e8f0;text-align:left}
.items-table td{padding:9px 6px;border-bottom:1px solid #f1f5f9;vertical-align:top}
.summary-row{display:flex;justify-content:space-between;gap:12px;font-size:13px;color:#334155;margin-bottom:8px}
.summary-row strong{font-size:16px;color:#0f172a}
@media(max-width:1100px){.billing-shell,.grid-2{grid-template-columns:1fr}}
</style>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;gap:12px;flex-wrap:wrap">
    <div>
        <a href="{{ route('admin.orders.index') }}" style="font-size:13px;color:var(--accent);font-weight:800;text-decoration:none">Back to orders</a>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:6px 0 0">Manual Billing</h2>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0">Counter sale bill banaiye aur turant print / save as PDF kijiye.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.orders.manualBilling.store') }}" id="billingForm">
    @csrf
    <div class="billing-shell">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Customer Details</p>
                    <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">Walk-in customer ke details</p>
                </div>
            </div>
            <div class="panel-body">
                <div class="grid-2">
                    <div class="field">
                        <label class="label">Customer Name *</label>
                        <input class="input" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')<p style="font-size:12px;color:#dc2626;margin-top:5px">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label class="label">Phone</label>
                        <input class="input" name="customer_phone" value="{{ old('customer_phone') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label class="label">Email</label>
                        <input class="input" type="email" name="customer_email" value="{{ old('customer_email') }}">
                    </div>
                    <div class="field">
                        <label class="label">GSTIN</label>
                        <input class="input" name="buyer_gst_no" value="{{ old('buyer_gst_no') }}">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Address</label>
                    <textarea class="textarea" name="customer_address">{{ old('customer_address') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label class="label">City</label>
                        <input class="input" name="customer_city" value="{{ old('customer_city') }}">
                    </div>
                    <div class="field">
                        <label class="label">State</label>
                        <input class="input" name="customer_state" value="{{ old('customer_state', 'Bihar') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label class="label">Pincode</label>
                        <input class="input" name="customer_pincode" value="{{ old('customer_pincode') }}">
                    </div>
                    <div class="field">
                        <label class="label">Drug License</label>
                        <input class="input" name="buyer_drug_license" value="{{ old('buyer_drug_license') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div>
                    <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Payment</p>
                    <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">Bill mode aur payment status</p>
                </div>
            </div>
            <div class="panel-body">
                <div class="grid-2">
                    <div class="field">
                        <label class="label">Payment Mode *</label>
                        <select class="select" name="payment_method" required>
                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                            <option value="upi" @selected(old('payment_method') === 'upi')>UPI</option>
                            <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                            <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                            <option value="credit" @selected(old('payment_method') === 'credit')>Credit</option>
                        </select>
                    </div>
                    <div class="field">
                        <label class="label">Amount Paid</label>
                        <input class="input" type="number" step="0.01" min="0" name="amount_paid" id="amountPaid" value="{{ old('amount_paid') }}">
                    </div>
                </div>

                <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:#334155;margin-bottom:14px">
                    <input type="hidden" name="is_inter_state" value="0">
                    <input type="checkbox" name="is_inter_state" value="1" @checked(old('is_inter_state'))>
                    Inter-state bill (IGST)
                </label>

                <div class="field">
                    <label class="label">Notes</label>
                    <textarea class="textarea" name="notes">{{ old('notes') }}</textarea>
                </div>

                <div style="border-top:1px solid #e2e8f0;padding-top:14px">
                    <div class="summary-row"><span>Taxable</span><strong id="sumTaxable">Rs. 0.00</strong></div>
                    <div class="summary-row"><span>GST</span><strong id="sumGst">Rs. 0.00</strong></div>
                    <div class="summary-row"><span>Grand Total</span><strong id="sumTotal">Rs. 0.00</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top:18px">
        <div class="panel-head">
            <div>
                <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Bill Items</p>
                <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">Product, batch, MRP, qty, discount aur GST</p>
            </div>
            <button type="button" class="btn-soft" id="addRowBtn"><i class="fas fa-plus"></i> Add Item</button>
        </div>
        <div style="overflow-x:auto">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="min-width:260px">Product / Variant</th>
                        <th>Batch</th>
                        <th>Exp</th>
                        <th>MRP</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Disc %</th>
                        <th>GST %</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="itemRows"></tbody>
            </table>
        </div>
        <div style="padding:16px 18px;display:flex;justify-content:flex-end;gap:10px">
            <a href="{{ route('admin.orders.index') }}" class="btn-soft">Cancel</a>
            <button type="submit" class="btn-primary"><i class="fas fa-print"></i> Create & Print Bill</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
const variants = @json($variantOptions);

let rowIndex = 0;
const rows = document.getElementById('itemRows');

function money(value) {
    return 'Rs. ' + Number(value || 0).toFixed(2);
}

function optionHtml() {
    return '<option value="">Select item</option>' + variants.map(item =>
        `<option value="${item.id}">${escapeHtml(item.label)} | Stock: ${item.stock}</option>`
    ).join('');
}

function addRow() {
    const index = rowIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select class="select item-variant" name="items[${index}][variant_id]" required>${optionHtml()}</select>
        </td>
        <td><input class="input item-batch" readonly></td>
        <td><input class="input item-expiry" readonly></td>
        <td><input class="input item-mrp" readonly></td>
        <td><input class="input item-qty" type="number" min="1" name="items[${index}][qty]" value="1" required></td>
        <td><input class="input item-price" type="number" step="0.01" min="0" name="items[${index}][unit_price]" value="0" required></td>
        <td><input class="input item-discount" type="number" step="0.01" min="0" max="100" name="items[${index}][discount_percent]" value="0"></td>
        <td><input class="input item-gst" readonly></td>
        <td style="font-weight:800;color:#0f172a;white-space:nowrap" class="item-total">Rs. 0.00</td>
        <td><button type="button" class="btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
    `;
    rows.appendChild(tr);
    bindRow(tr);
    calculateTotals();
}

function bindRow(row) {
    const variantSelect = row.querySelector('.item-variant');
    const recalcInputs = row.querySelectorAll('.item-qty,.item-price,.item-discount');

    variantSelect.addEventListener('change', () => {
        const item = variants.find(v => String(v.id) === String(variantSelect.value));
        row.querySelector('.item-batch').value = item?.batch || '';
        row.querySelector('.item-expiry').value = item?.expiry || '';
        row.querySelector('.item-mrp').value = item ? Number(item.mrp).toFixed(2) : '';
        row.querySelector('.item-price').value = item ? Number(item.price).toFixed(2) : '0';
        row.querySelector('.item-gst').value = item ? Number(item.gst).toFixed(2) : '';
        calculateTotals();
    });

    recalcInputs.forEach(input => input.addEventListener('input', calculateTotals));
    row.querySelector('.remove-row').addEventListener('click', () => {
        row.remove();
        if (!rows.children.length) addRow();
        calculateTotals();
    });
}

function calculateTotals() {
    let taxable = 0;
    let gst = 0;

    Array.from(rows.children).forEach(row => {
        const variantId = row.querySelector('.item-variant').value;
        const item = variants.find(v => String(v.id) === String(variantId));
        const qty = Number(row.querySelector('.item-qty').value || 0);
        const price = Number(row.querySelector('.item-price').value || 0);
        const discount = Number(row.querySelector('.item-discount').value || 0);
        const gstRate = Number(item?.gst || 0);
        const gross = qty * price;
        const lineTaxable = gross - (gross * discount / 100);
        const lineGst = lineTaxable * gstRate / 100;
        const lineTotal = lineTaxable + lineGst;

        taxable += lineTaxable;
        gst += lineGst;
        row.querySelector('.item-total').textContent = money(lineTotal);
    });

    document.getElementById('sumTaxable').textContent = money(taxable);
    document.getElementById('sumGst').textContent = money(gst);
    document.getElementById('sumTotal').textContent = money(taxable + gst);

    const paid = document.getElementById('amountPaid');
    if (!paid.value) paid.placeholder = Number(taxable + gst).toFixed(2);
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
}

document.getElementById('addRowBtn').addEventListener('click', addRow);
addRow();
</script>
@endsection
