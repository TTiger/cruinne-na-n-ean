<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Shop\Products\Models\Option;
use App\Shop\Products\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OptionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_defines_a_relationship_to_the_product(): void
    {
        /** @var Option $option */
        $option = Option::factory()->create([
            'vendor_id' => 987654321,
            'vendor_product_id' => 123456789,
        ]);
        /** @var Product $product */
        $product = Product::factory()->create([
            'vendor_id' => 123456789,
        ]);
        $option->product()->associate($product);

        $model = Option::with('product')->where('vendor_id', 987654321)->first();

        self::assertInstanceOf(Product::class, $model->product);
        self::assertEquals(123456789, $model->product->vendor_id);
    }
}
