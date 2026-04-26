<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        // Single image (featured)
        $this->addMediaCollection('featured')->singleFile();

        // Multiple images
        $this->addMediaCollection('gallery');
    }
}