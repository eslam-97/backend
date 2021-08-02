<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'quantity',
        'totalPrice',
        'user_id',
        'product_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];



    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return  $this->belongsToMany(Product::class, 'product_order', 'order_id','product_id');
    }
}
