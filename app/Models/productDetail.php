<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';

    protected $fillable = [
        'Model',
        'Processor',
        'cpuspeed',
        'VGA',
        'ram',
        'display',
        'harddisk',
        'battery',
        'OperatingSystem',
        'Camera',
        'color',
        'products_id',
        'simtype',
        'chipset',
        'internalstorage',
        'displayresolution',
        'selfiecamera',
        'fingerprint',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'created_at',
        'id',
        'products_id'
    ];

    public function product()
    {
        return  $this->belongsTo(Product::class, 'products_id');
    }

}
