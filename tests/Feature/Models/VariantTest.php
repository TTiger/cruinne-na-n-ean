<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Shop\Products\Models\Image;
use App\Shop\Products\Models\Option;
use App\Shop\Products\Models\Product;
use App\Shop\Products\Models\Variant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VariantTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_corrects_the_price_for_display(): void
    {
        /** @var Variant $variant */
        $variant = Variant::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
            'price' => 1234
        ]);

        self::assertEquals('12.34', $variant->price);
    }

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_product(): void
    {
        /** @var Variant $variant */
        $variant = Variant::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);
        $variant->product()->associate($product);

        $model = Variant::with('product')->where('vendor_id', 987654321)->first();

        self::assertInstanceOf(Product::class, $model->product);
        self::assertEquals(123456789, $model->product->vendor_id);
    }

    /**
     * @test
     */
    public function it_defines_a_relationship_to_an_image(): void
    {
        /** @var Variant $variant */
        $variant = Variant::factory()->create([
            'vendor_id' => 123456789,
            'vendor_image_id' => 987654321,
        ]);
        /** @var Image $image */
        $image = Image::factory()->create([
            'vendor_id' => 987654321,
        ]);
        $variant->image()->save($image);

        $model = Variant::with('image')->where('vendor_id', 123456789)->first();

        self::assertInstanceOf(Image::class, $model->image);
        self::assertEquals(987654321, $model->image->vendor_id);
    }

    /**
     * @ test
     */
    public function it_defines_a_relationship_to_order_lineitems(): void
    {
//        /** @var Variant $variant */
//        $variant = Variant::factory()->create([
//            'vendor_id' => 123456789,
//            'vendor_image_id' => 987654321,
//        ]);
//        /** @var Image $image */
//        $order = Order::factory()->create([
//            'vendor_id' => 987654321,
//        ]);
//        $variant->image()->save($image);
//
//        $model = Variant::with('image')->where('vendor_id', 123456789)->first();
//
//        self::assertInstanceOf(Image::class, $model->image);
//        self::assertEquals(987654321, $model->image->vendor_id);
    }
}
