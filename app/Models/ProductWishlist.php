<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductWishlist extends Model
{
    use HasFactory;

    protected $table = 'product_wishlist';
    protected $fillable = [
        'wishlist_id',
        'product_id',
       
    ];
}
