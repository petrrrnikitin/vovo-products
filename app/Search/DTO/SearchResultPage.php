<?php

declare(strict_types=1);

namespace App\Search\DTO;

use Illuminate\Support\Collection;

final readonly class SearchResultPage
{
    public function __construct(
        public Collection $items,
        public ?string $nextCursor,
        public ?string $prevCursor,
        public int $perPage,
    ) {}
}