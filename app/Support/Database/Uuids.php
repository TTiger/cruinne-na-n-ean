<?php

declare(strict_types=1);

namespace App\Support\Database;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

use function strtolower;

trait Uuids
{
    /**
     * Boot the trait.
     */
    protected static function bootUuids(): void
    {
        static::creating(static function ($model) {
            $uuid = Uuid::uuid4();

            if (isset($model->attributes[$model->getKeyName()]) && ! is_null($model->attributes[$model->getKeyName()])) {
                try {
                    $uuid = Uuid::fromString(strtolower($model->attributes[$model->getKeyName()]));
                } catch (InvalidUuidStringException $e) {
                    $uuid = Uuid::fromBytes($model->attributes[$model->getKeyName()]);
                }
            }

            $model->attributes[$model->getKeyName()] = strtolower($uuid->toString());
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * Get the auto-incrementing key name.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'uuid';
    }
}
