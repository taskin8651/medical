<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_variant_id',
        'product_name', 'variant_name', 'sku', 'hsn_code',
        'batch_number', 'expiry_date',
        'qty', 'mrp', 'unit_price',
        'discount_percent', 'discount_amount',
        'taxable_amount', 'gst_rate', 'gst_amount',
        'cgst', 'sgst', 'igst', 'total',
    ];
 
    protected $casts = [
        'expiry_date'      => 'date',
        'unit_price'       => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'gst_rate'         => 'decimal:2',
        'total'            => 'decimal:2',
    ];
 
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
 
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
 
    /**
     * Calculate all price fields and save.
     * Call this after setting qty + unit_price + gst_rate + discount_percent.
     * Detects inter-state order (IGST) vs intra-state (CGST + SGST).
     */
    public function calculateTotals(bool $isInterState = false): void
    {
        $gross            = $this->qty * $this->unit_price;
        $discountAmount   = round($gross * ($this->discount_percent / 100), 2);
        $taxable          = round($gross - $discountAmount, 2);
        $gstAmount        = round($taxable * ($this->gst_rate / 100), 2);
 
        $this->discount_amount = $discountAmount;
        $this->taxable_amount  = $taxable;
        $this->gst_amount      = $gstAmount;
 
        if ($isInterState) {
            $this->igst = $gstAmount;
            $this->cgst = 0;
            $this->sgst = 0;
        } else {
            $this->igst = 0;
            $this->cgst = round($gstAmount / 2, 2);
            $this->sgst = round($gstAmount / 2, 2);
        }
 
        $this->total = round($taxable + $gstAmount, 2);
    }
}