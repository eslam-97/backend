<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    
    protected $fillable = [
        'quantity',
        'totalPrice',
        'users_id',
       
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        ''
    ];



    public function user()
    {
        return  $this->belongsTo(User::class, 'users_id');
    }
    public function product()
    {
        return  $this->belongsToMany(Product::class, 'product_cart', 'cart_id','product_id')->withPivot('quantity');
    }
}
