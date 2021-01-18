<?php

declare(strict_types=1);

namespace App\Shop\Products\Transformers;

use App\Shop\Products\Models\Product;
use App\Support\Shopify\ShopifyTransformerInterface;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;
use Throwable;

class ProductTransformer implements ShopifyTransformerInterface
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * ProductTransformer constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function transform(Fluent $record): Model
    {
        try {
            $this->connection->beginTransaction();

            /** @var Product $product */
            // TODO: Replace Fluent $record with a DTO for Product
            $product = Product::query()->updateOrCreate(['vendor_id' => $record->get('id')], [
                'vendor_id' => $record->get('id'),
                'name' => $record->get('title'),
                'handle' => $record->get('handle'),
                'type' => $record->get('product_type'),
                'description' => $record->get('body_html'),
                'published_at' => Carbon::parse($record->get('published_at'))->toDateTime(),
                'created_at' => Carbon::parse($record->get('created_at'))->toDateTime(),
                'updated_at' => Carbon::parse($record->get('updated_at'))->toDateTime(),
            ]);

            if ($variants = $record->get('variants')) {
                foreach ($variants as $variant) {
                    // TODO: Replace with a DTO for Variant
                    $variant = new Fluent($variant);
                    $product->variants()->updateOrCreate([
                        'vendor_id' => $variant->get('id'),
                        'vendor_product_id' => $variant->get('product_id')
                    ], [
                        'vendor_id' => $variant->get('id'),
                        'vendor_product_id' => $variant->get('product_id'),
                        'vendor_image_id' => $variant->get('image_id'),
                        'name' => $variant->get('title'),
                        'price' => $variant->get('price') * 100,
                        'sku' => $variant->get('sku'),
                        'position' => $variant->get('position'),
                        'barcode' => $variant->get('barcode'),
                        'stock_qty' => $variant->get('inventory_quantity'),
                        'option1' => $variant->get('option1'),
                        'option2' => $variant->get('option2'),
                        'option3' => $variant->get('option3'),
                        'created_at' => Carbon::parse($variant->get('created_at'))->toDateTime(),
                        'updated_at' => Carbon::parse($variant->get('updated_at'))->toDateTime(),
                    ]);
                }
            }

            if ($options = $record->get('options')) {
                foreach ($options as $option) {
                    // TODO: Replace with a DTO for Option
                    $option = new Fluent($option);
                    $product->options()->updateOrCreate([
                        'vendor_id' => $option->get('id'),
                        'vendor_product_id' => $option->get('product_id'),
                    ], [
                        'vendor_id' => $option->get('id'),
                        'vendor_product_id' => $option->get('product_id'),
                        'name' => $option->get('name'),
                        'position' => $option->get('position'),
                        'values' => $option->get('values'),
                        'created_at' => Carbon::parse($option->get('created_at'))->toDateTime(),
                        'updated_at' => Carbon::parse($option->get('updated_at'))->toDateTime(),
                    ]);
                }
            }

            if ($images = $record->get('images')) {
                foreach ($images as $image) {
                    // TODO: Replace with a DTO for Image
                    $image = new Fluent($image);
                    $product->images()->updateOrCreate([
                        'vendor_id' => $image->get('id'),
                        'vendor_product_id' => $image->get('product_id'),
                    ], [
                        'vendor_id' => $image->get('id'),
                        'vendor_product_id' => $image->get('product_id'),
                        'src' => $image->get('src'),
                        'alt' => $image->get('alt'),
                        'width' => $image->get('width'),
                        'height' => $image->get('height'),
                        'position' => $image->get('position'),
                        'created_at' => Carbon::parse($image->get('created_at'))->toDateTime(),
                        'updated_at' => Carbon::parse($image->get('updated_at'))->toDateTime(),
                    ]);
                }
            }

            $this->connection->commit();

            return $product;
        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }
}