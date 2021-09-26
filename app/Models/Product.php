<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'en-name',
        'ar-name',
        'brands_id ',
        'productcode ',
        'availability',
        'discount',
        'totalprice',
        'type',
        'image',
        'image-2',
        'shopping_carts_id',
        'wish_lists_id',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function brand()
    {
        return  $this->belongsTo(Brand::class, 'brands_id');
    }
    public function productDetail()
    {
        return  $this->hasOne(productDetail::class, 'products_id');
    }
    public function rating()
    {
        return  $this->hasMany(rating::class, 'product_id');
    }
    public function ratingOrder()
    {
        return  $this->hasMany(rating::class, 'product_id')->orderBy('rate');
    }
    public function cart()
    {
        return  $this->belongsToMany(Cart::class, 'product_cart','product_id','cart_id');
    }
    public function wishlist()
    {
        return  $this->belongsToMany(Wishlist::class, 'product_wishlist','product_id', 'wishlist_id');
    }
    public function order()
    {
        return  $this->belongsToMany(order::class, 'product_order','product_id', 'order_id');
    }
}
