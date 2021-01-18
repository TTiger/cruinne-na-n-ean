<?php

namespace App\Shop\Products\Models;

use App\Support\Database\Model;
use Database\Factories\OptionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'vendor_id' => 'int',
        'vendor_product_id' => 'int',
        'position' => 'int',
        'values' => 'array',
    ];

    /**
     * The relationship to the product.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'vendor_product_id', 'vendor_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return new OptionFactory();
    }
}
