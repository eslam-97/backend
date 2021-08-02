<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rating extends Model
{
    use HasFactory;
    

    protected $table = 'ratings';

    protected $fillable = [
        'text',
        'rate',
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
        return  $this->belongsTo(user::class, 'user_id');
    }
    public function product()
    {
        return  $this->belongsTo(Product::class, 'product_id');
    }
}
