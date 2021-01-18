<?php

declare(strict_types=1);

namespace App\Support\Shopify;

use function explode;
use function preg_match;

class PaginationLinks
{
    /**
     * @var PaginationLink|null
     */
    private ?PaginationLink $previous;

    /**
     * @var PaginationLink|null
     */
    private ?PaginationLink $next;

    /**
     * PaginationLink constructor.
     *
     * @param string $links
     */
    public function __construct(string $links)
    {
        ['previous' => $previous, 'next' => $next] = $this->extract($links);

        $this->previous = $previous ? new PaginationLink($previous) : null;
        $this->next = $next ? new PaginationLink($next) : null;
    }

    /**
     * The previous pagination link.
     *
     * @return ?PaginationLink
     */
    public function previous(): ?PaginationLink
    {
        return $this->previous;
    }

    /**
     * The next pagination link.
     *
     * @return ?PaginationLink
     */
    public function next(): ?PaginationLink
    {
        return $this->next;
    }

    /**
     * Extract pagination links,
     *
     * @param string $links
     *
     * @return array
     */
    private function extract(string $links): array
    {
        $pattern = '/<([a-zA-Z0-9:\/\/\-.?_=&]+)>; rel="([a-zA-Z]+)",? ?"?/';

        $segments = explode(', ', $links);

        $extracted = [];

        foreach ($segments as $link) {
            if (preg_match($pattern, $link, $matches)) {
                $extracted[$matches[2]] = $matches[1];
            }
        }

        if (! isset($extracted['previous'])) {
            $extracted['previous'] = null;
        }

        if (! isset($extracted['next'])) {
            $extracted['next'] = null;
        }

        return $extracted;
    }
}
