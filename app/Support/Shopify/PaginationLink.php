<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use Illuminate\Contracts\Support\Arrayable;

use function parse_str;
use function parse_url;

class PaginationLink implements Arrayable
{
    /**
     * @var string|null
     */
    private ?string $link;

    /**
     * PaginationLink constructor.
     *
     * @param string|null $link
     */
    public function __construct(?string $link)
    {
        $this->link = $link;
    }

    /**
     * The pagination link url.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->link ?? '';
    }

    /**
     * The previous pagination link.
     *
     * @return array
     */
    public function params(): array
    {
        if (null === $this->link) {
            return [];
        }

        parse_str(parse_url($this->link, PHP_URL_QUERY), $result);

        return $result;
    }

    /**
     * Cast the object to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->url();
    }

    /**
     * Cast the object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        if (null === $this->link) {
            return [];
        }

        return parse_url($this->link);
    }
}
