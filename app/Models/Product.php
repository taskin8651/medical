<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Product extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'category_id', 'subcategory_id', 'brand_id',
        'name', 'slug', 'sku', 'short_description', 'description',
 
        // Medical fields
        'generic_name', 'composition', 'hsn_code',
        'drug_schedule', 'requires_prescription',
        'form', 'strength', 'storage_conditions',
        'side_effects', 'contraindications', 'shelf_life',
 
        // Pricing
        'mrp', 'ptr', 'pts', 'price', 'sale_price', 'gst_rate',
 
        // Pack
        'pack_size', 'pack_type', 'units_per_pack',
 
        // Wholesale & stock
        'min_qty', 'max_qty', 'stock',
 
        // Status
        'is_active', 'is_featured',
    ];
 
    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active'             => 'boolean',
        'is_featured'           => 'boolean',
        'mrp'                   => 'decimal:2',
        'ptr'                   => 'decimal:2',
        'pts'                   => 'decimal:2',
        'price'                 => 'decimal:2',
        'sale_price'            => 'decimal:2',
        'gst_rate'              => 'decimal:2',
    ];
 
    // ---- Relationships ----
 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
 
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
 
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
 
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
 
    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }
 
    public function images()
    {
        return $this->hasMany(ProductMedia::class)->where('type', 'image')->orderBy('sort_order');
    }
 
    public function primaryImage()
    {
        return $this->hasOne(ProductMedia::class)->where('type', 'image')->where('is_primary', true);
    }
 
    public function documents()
    {
        return $this->hasMany(ProductMedia::class)->whereIn('type', ['brochure', 'certificate', 'prescription_sample']);
    }
 
    // ---- Helpers ----
 
    /** Effective selling price (sale price if available, else base price) */
    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }
 
    /** GST amount on effective price */
    public function getGstAmountAttribute(): float
    {
        return round($this->effective_price * $this->gst_rate / 100, 2);
    }
 
    /** Price + GST */
    public function getPriceWithGstAttribute(): float
    {
        return round($this->effective_price + $this->gst_amount, 2);
    }
 
    /** Margin % = (ptr - pts) / ptr * 100 */
    public function getMarginPercentAttribute(): ?float
    {
        if ($this->ptr && $this->pts) {
            return round(($this->ptr - $this->pts) / $this->ptr * 100, 2);
        }
        return null;
    }
 
    // ---- Scopes ----
 
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
 
    public function scopeRequiresPrescription($query)
    {
        return $query->where('requires_prescription', true);
    }
 
    public function scopeSchedule($query, string $schedule)
    {
        return $query->where('drug_schedule', $schedule);
    }
 
    public function scopeOtc($query)
    {
        return $query->where('drug_schedule', 'OTC')
                     ->orWhere('requires_prescription', false);
    }
}
 