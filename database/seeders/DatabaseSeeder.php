<?php

namespace Database\Seeders;

use App\Models\LaptopDetail;
use App\Models\Product;
use App\Models\rating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(2)->has(rating::factory()->count(30))->create();
        // DB::table('products')->update([
        //     'sold' => rand(0,50)
        // ]);
        // $products = LaptopDetail::get();
        // $colors = array('black','red','gold','grey','white','blue','green');
        // foreach($products as $product ){
        //     $product->color = $colors[array_rand($colors)];
        //     $product->save();
        // }
     }
}
