<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }
}