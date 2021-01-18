<?php

declare(strict_types=1);

namespace App\Shop\Products\Reports;

use App\Shop\Orders\Models\Lineitem;
use App\Shop\Products\Models\Variant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use stdClass;

class MostPurchasedReport
{
    /**
     * Run the report.
     *
     * @param int $perPage
     *
     * @return LengthAwarePaginator
     */
    public function run($perPage = 10): LengthAwarePaginator
    {
        $results = $this->query()->paginate($perPage);

        $results->getCollection()->transform(function ($result) {
            return $this->transform($result);
        });

        return $results;
    }

    /**
     * The report query.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return Variant::query()
            ->select([
                'products.name as product_name',
                'variants.name',
                'variant_images.src as variant_image_src',
                'product_images.src as product_image_src',
                'variants.price',
                'variants.stock_qty',
            ])
            ->selectSub(
                Lineitem::query()
                    ->distinct()
                    ->selectRaw('COUNT(lineitems.vendor_id)')
                    ->groupBy('lineitems.vendor_variant_id')
                    ->havingRaw('lineitems.vendor_variant_id = li.vendor_variant_id')
                    ->limit(1),
                'total_purchase_count'
            )
            ->leftJoin('products', 'products.vendor_id', '=', 'variants.vendor_product_id')
            ->leftJoin('images as variant_images', 'variant_images.vendor_id', '=', 'variants.vendor_image_id')
            ->leftJoin('images as product_images', 'products.vendor_id', '=', 'product_images.vendor_product_id')
            ->leftJoin('lineitems as li', 'li.vendor_variant_id', '=', 'variants.vendor_id')
            ->groupByRaw(
                'variants.vendor_id, products.name, variants.name, product_images.src, variant_images.src, variants.price, variants.stock_qty'
            )
            ->orderByDesc('total_purchase_count')
            ->toBase();
    }

    /**
     * Transform each record of the collection.
     *
     * @param stdClass $result
     *
     * @return Fluent
     */
    public function transform(stdClass $result): Fluent
    {
        return new Fluent([
            'variant' => Str::title($result->name),
            'product' => Str::title($result->product_name),
            'image_url' => $this->formatImageUrl($result),
            'image_alt' => $this->formatImageAlt($result),
            'currency' => 'USD',
            'price' => $this->formatPrice($result),
            'total_purchase_count' => $result->total_purchase_count ?? 0,
            'stock_qty' => $result->stock_qty ?? 0,
            'total_order_value' => number_format($this->formatTotalOrderValue($result), 2)
        ]);
    }

    /**
     * Format the price.
     *
     * @param stdClass $result
     *
     * @return float|int
     */
    protected function formatPrice(stdClass $result): float|int
    {
        return $result->price / 100;
    }

    /**
     * Format the total order value.
     *
     * @param stdClass $result
     * @return float|int
     */
    protected function formatTotalOrderValue(stdClass $result): float|int
    {
        return $this->formatPrice($result) * $result->total_purchase_count;
    }

    /**
     * Format the image url.
     *
     * @param stdClass $result
     *
     * @return string
     */
    protected function formatImageUrl(stdClass $result): string
    {
        if (!empty($variantImage = $result->variant_image_src)) {
            return $variantImage;
        }

        if (!empty($productImage = $result->product_image_src)) {
            return $productImage;
        }

        return $this->formatDefaultImage($result);
    }

    /**
     * The default image for a product.
     *
     * @param stdClass $result
     *
     * @return string
     */
    protected function formatDefaultImage(stdClass $result): string
    {
        $name = urlencode($result->product_name);

        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

    /**
     * Format the image alt text.
     *
     * @param stdClass $result
     *
     * @return string
     */
    protected function formatImageAlt(stdClass $result): string
    {
        return Str::title($result->product_name . ' - ' . $result->name);
    }
}
