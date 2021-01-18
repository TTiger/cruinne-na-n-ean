<?php

declare(strict_types=1);

namespace App\Shop\Orders\Transformers;

use App\Shop\Orders\Models\Order;
use App\Support\Shopify\ShopifyTransformerInterface;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;
use Throwable;

class OrderTransformer implements ShopifyTransformerInterface
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

            if (! $record->get('order_number')) {
                logger()->info('Order number missing', compact('record'));
            }

            /** @var Order $order */
            $order = Order::query()->updateOrCreate([
                'vendor_id' => $record->get('id')
            ], [
                'vendor_id' => $record->get('id'),
                'vendor_number' => $record->get('order_number'),
                'name' => $record->get('name'),
                'number' => $record->get('number'),
                'subtotal' => $record->get('subtotal_price') * 100,
                'tax' => $record->get('total_tax') * 100,
                'total' => $record->get('total_price') * 100,
                'currency' => $record->get('currency'),
                'processed_at' => Carbon::parse($record->get('processed_at'))->toDateTime(),
                'created_at' => Carbon::parse($record->get('created_at'))->toDateTime(),
                'updated_at' => Carbon::parse($record->get('updated_at'))->toDateTime(),
            ]);

            if ($lineitems = $record->get('line_items')) {
                foreach ($lineitems as $lineitem) {
                    $lineitem = new Fluent($lineitem);
                    $order->lineitems()->updateOrCreate([
                        'vendor_id' => $lineitem->get('id'),
                        'vendor_order_id' => $record->get('id')
                    ], [
                        'vendor_id' => $lineitem->get('id'),
                        'vendor_order_id' => $record->get('id'),
                        'vendor_variant_id' => $lineitem->get('variant_id'),
                        'vendor_product_id' => $lineitem->get('product_id'),
                        'price' => $lineitem->get('price') * 100,
                        'quantity' => $lineitem->get('quantity'),
                    ]);
                }
            }

            $this->connection->commit();

            return $order;
        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }
}
