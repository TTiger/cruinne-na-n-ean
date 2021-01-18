<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Shop\Products\Models\Image;
use App\Shop\Products\Models\Option;
use App\Shop\Products\Models\Product;
use App\Shop\Products\Models\Variant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_product(): void
    {
        /** @var Image $image */
        $image = Image::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);

        $image->product()->associate($product);

        $model = Image::with('product')->where('vendor_id', 987654321)->first();

        self::assertInstanceOf(Product::class, $model->product);
        self::assertEquals(123456789, $model->product->vendor_id);
    }
}
