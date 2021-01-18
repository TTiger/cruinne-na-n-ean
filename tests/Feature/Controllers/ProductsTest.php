<?php

namespace Tests\Feature\Controllers;

use App\Shop\Products\Models\Variant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_displays_the_product_index_view(): void
    {
        Variant::factory()->count(5)->create();

        $this->withoutExceptionHandling()
            ->get('/')
            ->assertStatus(200)
            ->assertViewIs('product.index');
    }
}
