<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }
}