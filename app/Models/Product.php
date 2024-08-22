<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id', 'id');
    }


    public function wishlist(){
        return $this->hasMany(Wishlist::class, 'product_id', 'id');
    }


    public function galeri(){
        return $this->hasMany(GalleriProduct::class, 'product_id', 'id');
    }
}
