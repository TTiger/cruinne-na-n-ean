<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use Illuminate\Support\LazyCollection;

class Response
{
    /**
     * @var LazyCollection
     */
    private LazyCollection $records;

    /**
     * @var PaginationLink|null
     */
    private ?PaginationLink $next;

    /**
     * @var PaginationLink|null
     */
    private ?PaginationLink $previous;

    /**
     * Response constructor.
     *
     * @param LazyCollection $records
     * @param PaginationLinks $paginationLinks
     */
    public function __construct(LazyCollection $records, PaginationLinks $paginationLinks)
    {
        $this->records = $records;
        $this->previous = $paginationLinks->previous();
        $this->next = $paginationLinks->next();
    }

    /**
     * The records fetched from Shopify.
     *
     * @return LazyCollection
     */
    public function records(): LazyCollection
    {
        return $this->records;
    }

    /**
     * The next previous link.
     *
     * @return PaginationLink|null
     */
    public function previous(): ?PaginationLink
    {
        return $this->previous;
    }

    /**
     * The next pagination link.
     *
     * @return PaginationLink|null
     */
    public function next(): ?PaginationLink
    {
        return $this->next;
    }
}
