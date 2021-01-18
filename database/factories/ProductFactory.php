<?php

namespace Database\Factories;

use App\Shop\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'uuid' => Str::uuid(),
            'vendor_id' => $this->faker->unique()->numberBetween(100000, 900000),
            'name' => $this->faker->unique()->name(),
            'handle' => $this->faker->unique()->name(),
            'type' => 'foo',
            'description' => $this->faker->sentences(3, true),
            'published_at' => now()->toDateTimeString()
        ];
    }
}
