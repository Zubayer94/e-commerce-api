<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence($nbWords = 4, $variableNbWords = true);
        $slug = Str::slug($title, '-');
        return [
            'title' => $title,
            'slug' => $slug,
            'price' => $this->faker->numberBetween($min = 500, $max = 10000),
            'image' => 'https://picsum.photos/1200/900',
            'description' => $this->faker->paragraph($nbSentences = 3, $variableNbSentences = true),
            'qty' => $this->faker->numberBetween($min = 1, $max = 50),
            'category_id' => Category::factory()->create(),
        ];
    }
}
