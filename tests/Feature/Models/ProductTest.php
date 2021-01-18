<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Shop\Products\Models\Image;
use App\Shop\Products\Models\Option;
use App\Shop\Products\Models\Product;
use App\Shop\Products\Models\Variant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_variant(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);
        /** @var Variant $variant */
        $variant = Variant::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        $product->variants()->save($variant);

        $model = Product::with('variants')->where('vendor_id', 123456789)->first();

        self::assertInstanceOf(Variant::class, $model->variants->first());
        self::assertEquals(987654321, $model->variants->first()->vendor_id);
    }

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_options(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);
        /** @var Option $option */
        $option = Option::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        $product->options()->save($option);

        $model = Product::with('options')->where('vendor_id', 123456789)->first();

        self::assertInstanceOf(Option::class, $model->options->first());
        self::assertEquals(987654321, $model->options->first()->vendor_id);
    }

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_images(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);
        /** @var Image $image */
        $image = Image::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        $product->images()->save($image);

        $model = Product::with('images')->where('vendor_id', 123456789)->first();

        self::assertInstanceOf(Image::class, $model->images->first());
        self::assertEquals(987654321, $model->images->first()->vendor_id);
    }
}
