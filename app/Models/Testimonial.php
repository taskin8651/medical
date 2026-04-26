<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'designation',
        'message',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        // single image (client photo)
        $this->addMediaCollection('testimonial')->singleFile();
    }
}