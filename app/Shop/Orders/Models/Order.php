<?php

namespace App\Shop\Orders\Models;

use App\Support\Database\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'vendor_id' => 'int',
        'vendor_number' => 'int',
        'number' => 'int',
        'subtotal' => 'int',
        'tax' => 'int',
        'total' => 'int',
    ];

    /**
     * The relationship to the lineitems.
     *
     * @return HasMany
     */
    public function lineitems(): HasMany
    {
        return $this->hasMany(Lineitem::class, 'vendor_id', 'vendor_order_id');
    }
}
