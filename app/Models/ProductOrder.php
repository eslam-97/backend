<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductOrder extends Model
{
    use HasFactory;


    protected $table = 'product_order';
    protected $fillable = [
        'order_id',
        'product_id',
       
    ];
}
