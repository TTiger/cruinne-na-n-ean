<?php

namespace App\Shop\Orders\Models;

use App\Support\Database\Model;
use App\Support\Database\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lineitem extends Model
{
    use HasFactory;
    use Uuids;

    /**
     * Get the variant price.
     *
     * @return string
     */
    public function getPriceAttribute(): string
    {
        return number_format($this->attributes['price'] / 100, 2);
    }

    /**
     * The relationship to the order.
     *
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'vendor_order_id', 'vendor_id');
    }

    /**
     * The relationship to the variant.
     *
     * @return HasOne
     */
    public function variant(): HasOne
    {
        return $this->hasOne(Variant::class, 'vendor_order_id', 'vendor_id');
    }
}
