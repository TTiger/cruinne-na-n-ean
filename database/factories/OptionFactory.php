<?php

namespace Database\Factories;

use App\Shop\Products\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor_id' => $this->faker->unique()->numberBetween(10000, 90000),
            'vendor_product_id' => $this->faker->unique()->numberBetween(10000, 90000),
            'name' => '',
            'position' => 1,
            'values' => null
        ];
    }
}
