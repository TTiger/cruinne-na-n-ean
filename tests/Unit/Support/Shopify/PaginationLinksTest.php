<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Shopify;

use App\Support\Shopify\PaginationLink;
use App\Support\Shopify\PaginationLinks;
use PHPUnit\Framework\TestCase;

class PaginationLinksTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_parse_a_pagination_link_header_with_a_next_url(): void
    {
        $header = '<https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=5&page_info=eyJsYXN0X2lkIjo3NTg3NTYxNDc0MCwibGFzdF92YWx1ZSI6IkdpZnQgQ2FyZCIsImRpcmVjdGlvbiI6Im5leHQifQ>; rel="next"';

        $paginationLinks = new PaginationLinks($header);
        $next = $paginationLinks->next();

        self::assertInstanceOf(PaginationLink::class, $next);
        self::assertEquals(
            'https://universe-of-birds.myshopify.com/admin/api/2020-04/products.json?limit=5&page_info=eyJsYXN0X2lkIjo3NTg3NTYxNDc0MCwibGFzdF92YWx1ZSI6IkdpZnQgQ2FyZCIsImRpcmVjdGlvbiI6Im5leHQifQ',
            (string) $next
        );
    }
}
