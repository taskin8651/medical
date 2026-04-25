<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'product_id', 'name', 'sku', 'barcode',
        'strength', 'pack_size', 'pack_type',
        'batch_number', 'expiry_date', 'manufacturer_batch_no',
        'mrp', 'ptr', 'pts', 'price', 'gst_rate',
        'stock', 'low_stock_alert', 'is_active',
    ];
 
    protected $casts = [
        'expiry_date' => 'date',
        'is_active'   => 'boolean',
        'mrp'         => 'decimal:2',
        'ptr'         => 'decimal:2',
        'pts'         => 'decimal:2',
        'price'       => 'decimal:2',
        'gst_rate'    => 'decimal:2',
    ];
 
    // ---- Relationships ----
 
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
 
    public function tierPricings()
    {
        return $this->hasMany(TierPricing::class);
    }
 
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
 
    // ---- Helpers ----
 
    /** Get wholesale price for given quantity and customer type */
    public function getPriceForQty(int $qty, string $customerType = 'all'): float
    {
        $tier = $this->tierPricings()
            ->where('is_active', true)
            ->where('min_qty', '<=', $qty)
            ->where(function ($q) use ($qty) {
                $q->whereNull('max_qty')->orWhere('max_qty', '>=', $qty);
            })
            ->where(function ($q) use ($customerType) {
                $q->where('customer_type', $customerType)
                  ->orWhere('customer_type', 'all');
            })
            ->orderByDesc('min_qty')
            ->first();
 
        return $tier ? $tier->price_per_unit : $this->price;
    }
 
    /** GST rate: variant-level or falls back to product-level */
    public function getEffectiveGstRateAttribute(): float
    {
        return $this->gst_rate ?? $this->product->gst_rate;
    }
 
    /** Is stock running low? */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->low_stock_alert;
    }
 
    /** Is product near expiry? (within 90 days) */
    public function getIsNearExpiryAttribute(): bool
    {
        return $this->expiry_date
            && $this->expiry_date->diffInDays(now()) <= 90;
    }
 
    // ---- Scopes ----
 
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
 
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
 
    public function scopeExpiringSoon($query, int $days = 90)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<=', now()->addDays($days));
    }
}
 
 