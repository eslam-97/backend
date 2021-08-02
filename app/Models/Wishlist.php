<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlists';
    
    protected $fillable = [
        'users_id',
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
        return  $this->belongsTo(User::class, 'User_id');
    }
    public function product()
    {
        return  $this->belongsToMany(Product::class, 'product_wishlist', 'wishlist_id','product_id');
    }
}
