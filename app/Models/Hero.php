<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'subtitle',
    'description',
    'button_text',
    'button_link',
    'image',
    'status'
];
}
