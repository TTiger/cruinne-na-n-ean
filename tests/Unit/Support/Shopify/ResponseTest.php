<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Shopify;

use App\Support\Shopify\PaginationLinks;
use App\Support\Shopify\Response;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_the_link_url_in_full(): void
    {
        $records = new LazyCollection();
        $paginationLinks = new PaginationLinks(
            '<https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=3&page_info=eyJkaXJlY3Rpb24iOiJwcmV2IiwibGFzdF9pZCI6NDY0OTY3OTI1NzcwMiwibGFzdF92YWx1ZSI6IkRlbGV0ZSBWYXJpYW50In0>; rel="previous", <https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=3&page_info=eyJkaXJlY3Rpb24iOiJuZXh0IiwibGFzdF9pZCI6MTY2Mjk3NzQ3NDY2MiwibGFzdF92YWx1ZSI6IkdvLVRvIFBvbG8gTmF2eSJ9>; rel="next"'
        );

        $response = new Response($records, $paginationLinks);

        self::assertEquals($response->records(), $records);
        self::assertEquals((string) $paginationLinks->next(), (string) $response->next());
        self::assertEquals((string) $paginationLinks->previous(), (string) $response->previous());
    }
}
