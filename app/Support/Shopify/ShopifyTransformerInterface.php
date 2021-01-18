<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;

interface ShopifyTransformerInterface
{
    /**
     * Transform a product from the Shopify API into an eloquent model.
     *
     * @param Fluent $record
     *
     * @return mixed
     */
    public function transform(Fluent $record): Model;
}
