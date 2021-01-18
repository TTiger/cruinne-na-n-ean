<?php

namespace App\Shop\Products\Models;

use App\Shop\Orders\Models\Lineitem;
use App\Support\Database\Model;
use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Variant extends Model
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
    ];

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
     * The relationship to the product.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'vendor_product_id', 'vendor_id');
    }

    /**
     * The relationship to the image.
     *
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'vendor_id', 'vendor_image_id');
    }

    /**
     * The relationship to the ordered lineitems.
     *
     * @return BelongsTo
     */
    public function lineitems(): BelongsTo
    {
        return $this->belongsTo(Lineitem::class, 'variants', 'vendor_id', 'vendor_variant_id')->orderBy('position');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return new VariantFactory();
    }
}
