<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'UID' => 'Or-' . time() . Str::random(1) . rand(10, 99),
            'product_price' => $this->faker->numberBetween($min = 500, $max = 10000),
            'status' => 'Processing',
            'unit' => 1,
            'product_id' => Product::factory()->create(),
            'user_id' => User::factory()->create(),
        ];
    }
}
