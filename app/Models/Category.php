<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    // 🔥 Relationships
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 🔥 Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // 🔥 Media Collection
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category')->singleFile();
    }

    // 🔥 Thumbnail (optional but recommended)
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);
    }

    // 🔥 Helper (clean URL access)
    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('category') ?: asset('default/category.png');
    }
}