<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Shopify;

use App\Support\Shopify\PaginationLink;
use PHPUnit\Framework\TestCase;

class PaginationLinkTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_the_link_url_in_full(): void
    {
        $paginationUrl = 'https://foo.myshopify.com/admin/api/2020-04/products.json?page_info=opqrstu&limit=3';
        $paginationLink = new PaginationLink($paginationUrl);

        static::assertEquals($paginationUrl, $paginationLink->url());
    }

    /**
     * @test
     */
    public function it_provides_the_link_as_url_components(): void
    {
        $paginationUrl = 'https://foo.myshopify.com/admin/api/2020-04/products.json?page_info=opqrstu&limit=3';
        $paginationLink = new PaginationLink($paginationUrl);

        static::assertEquals(parse_url($paginationUrl), $paginationLink->toArray());
    }

    /**
     * @test
     */
    public function it_provides_the_link_url_params(): void
    {
        $paginationUrl = 'https://foo.myshopify.com/admin/api/2020-04/products.json?page_info=opqrstu&limit=3';
        $paginationLink = new PaginationLink($paginationUrl);

        $params = $paginationLink->params();

        static::assertArrayHasKey('page_info', $params);
        static::assertEquals('opqrstu', $params['page_info']);
        static::assertArrayHasKey('limit', $params);
        static::assertEquals(3, $params['limit']);
    }
}
