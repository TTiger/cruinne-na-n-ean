<?php

namespace Database\Factories;

use App\Shop\Products\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor_id' => $this->faker->unique()->numberBetween(100000, 900000),
            'vendor_product_id' => $this->faker->unique()->numberBetween(100000, 900000),
            'src' => $this->faker->imageUrl(800, 600),
            'alt' => '',
            'width' => 800,
            'height' => 600,
            'position' => 1,
        ];
    }
}
