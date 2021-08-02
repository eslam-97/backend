<?php

namespace Database\Factories;

use App\Models\rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class ratingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->words(10,25),
            'rate' => mt_rand(1,5),
            'product_id' => mt_rand(2,150),
        ];
    }

}
