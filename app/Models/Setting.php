<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    // All fields mass assignable
    protected $guarded = [];

    /**
     * Type Casting
     */
    protected $casts = [
        'popup_status' => 'boolean',
    ];

    /**
     * Get Single Setting Row (Singleton Pattern)
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([]);
    }

    /**
     * Get Logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('default/logo.png');
    }

    /**
     * Get Favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon 
            ? asset('storage/' . $this->favicon) 
            : asset('default/favicon.png');
    }

    /**
     * Get OG Image URL
     */
    public function getOgImageUrlAttribute()
    {
        return $this->og_image 
            ? asset('storage/' . $this->og_image) 
            : asset('default/og.png');
    }

    /**
     * Get Popup Image URL
     */
    public function getPopupImageUrlAttribute()
    {
        return $this->popup_image 
            ? asset('storage/' . $this->popup_image) 
            : null;
    }

    /**
     * Helper: Social Links Array
     */
    public function getSocialLinks()
    {
        return [
            'facebook'  => $this->facebook,
            'instagram' => $this->instagram,
            'twitter'   => $this->twitter,
            'linkedin'  => $this->linkedin,
            'youtube'   => $this->youtube,
            'whatsapp'  => $this->whatsapp,
        ];
    }

    /**
     * Helper: SEO Data
     */
    public function getSeo()
    {
        return [
            'title'       => $this->meta_title,
            'description' => $this->meta_description,
            'keywords'    => $this->meta_keywords,
            'og_image'    => $this->og_image_url,
        ];
    }
}