<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductCart extends Model
{
    use HasFactory;

    protected $table = 'product_cart';
    protected $fillable = [
        'cart_id',
        'product_id',
       
    ];
}