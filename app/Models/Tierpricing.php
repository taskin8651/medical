<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class TierPricing extends Model
{
    protected $fillable = [
        'product_variant_id', 'min_qty', 'max_qty',
        'price_per_unit', 'discount_percent', 'customer_type', 'is_active',
    ];
 
    protected $casts = [
        'is_active'        => 'boolean',
        'price_per_unit'   => 'decimal:2',
        'discount_percent' => 'decimal:2',
    ];
 
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
 
    public function getRangeLabel(): string
    {
        if (is_null($this->max_qty)) {
            return "{$this->min_qty}+ units";
        }
        return "{$this->min_qty} – {$this->max_qty} units";
    }
}