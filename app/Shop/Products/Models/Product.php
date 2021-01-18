<?php

namespace App\Shop\Products\Models;

use App\Support\Database\Model;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The relationship to the variants.
     *
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class, 'vendor_product_id', 'vendor_id')->orderBy('position');
    }

    /**
     * The relationship to the options.
     *
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'vendor_product_id', 'vendor_id')->orderBy('position');
    }

    /**
     * The relationship to the images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'vendor_product_id', 'vendor_id')->orderBy('position');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return new ProductFactory();
    }
}
