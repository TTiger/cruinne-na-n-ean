<?php

declare(strict_types=1);

namespace App\Support\Shopify;

interface ShopifyClientInterface
{
    /**
     * The product details are complete and the product is available to be displayed.
     *
     * @var string
     */
    public const ACTIVE = 'active';

    /**
     * The product details need to be completed before it can be displayed.
     *
     * @var string
     */
    public const DRAFT = 'draft';

    /**
     * The product details are complete, but the product is no longer for sale.
     *
     * @var string
     */
    public const ARCHIVED = 'archived';

    /**
     * Fetch the products from the Shopify API.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function products(array $params = []): Response;

    /**
     * Fetch the orders from the Shopify API.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function orders(array $params = []): Response;
}
