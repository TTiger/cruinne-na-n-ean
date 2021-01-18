<?php

namespace Database\Factories;

use App\Shop\Products\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Variant::class;

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
            'vendor_image_id' => $this->faker->unique()->numberBetween(100000, 900000),
            'name' => $this->faker->name,
            'price' => 350,
            'sku' => $this->faker->slug,
            'position' => 0,
            'barcode' => null,
            'stock_qty' => 1,
            'option1' => null,
            'option2' => null,
            'option3' => null,
        ];
    }
}
