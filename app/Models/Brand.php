<?php

namespace App\Models;

 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Brand extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'name', 'slug', 'manufacturer_name',
        'drug_license_no', 'gst_no', 'logo', 'is_active',
    ];
 
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}