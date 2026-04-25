<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Order extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'order_number', 'user_id', 'buyer_gst_no', 'buyer_drug_license',
        'billing_address', 'shipping_address',
        'subtotal', 'total_gst', 'cgst', 'sgst', 'igst',
        'discount_amount', 'coupon_code', 'shipping_charge', 'total',
        'payment_status', 'payment_method', 'payment_terms',
        'due_date', 'amount_paid',
        'invoice_number', 'invoice_date',
        'status', 'dispatch_mode', 'tracking_number',
        'dispatched_at', 'delivered_at',
        'notes', 'internal_notes',
    ];
 
    protected $casts = [
        'billing_address'  => 'array',  // Store as JSON {name, address, city, state, pin, phone}
        'shipping_address' => 'array',
        'due_date'         => 'date',
        'invoice_date'     => 'date',
        'dispatched_at'    => 'datetime',
        'delivered_at'     => 'datetime',
        'subtotal'         => 'decimal:2',
        'total'            => 'decimal:2',
        'total_gst'        => 'decimal:2',
        'amount_paid'      => 'decimal:2',
    ];
 
    // ---- Relationships ----
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
 
    // ---- Helpers ----
 
    /** Amount still due */
    public function getBalanceDueAttribute(): float
    {
        return max(0, $this->total - $this->amount_paid);
    }
 
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->amount_paid >= $this->total;
    }
 
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && now()->gt($this->due_date)
            && !$this->is_fully_paid;
    }
 
    /** Generate next invoice number: INV-2025-00001 */
    public static function generateInvoiceNumber(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
 
    /** Generate order number */
    public static function generateOrderNumber(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'ORD-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
 
    // ---- Scopes ----
 
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
 
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', '!=', 'paid')
                     ->whereNotNull('due_date')
                     ->where('due_date', '<', now());
    }
}
 