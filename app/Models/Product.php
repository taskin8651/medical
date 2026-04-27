<?php


namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
 
class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
 
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
 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }
        });
    }
 
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
 
    // Spatie Media Library relationships
    public function images()
    {
        return $this->media()->where('collection_name', 'images');
    }
 
    public function primaryImage()
    {
        return $this->media()->where('collection_name', 'images')->where('custom_properties->is_primary', true)->first();
    }
 
    public function documents()
    {
        return $this->media()->where('collection_name', 'documents');
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
 