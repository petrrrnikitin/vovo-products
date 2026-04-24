<?php

declare(strict_types=1);

namespace App\DTO;

use App\Contracts\Search\ProductSearchFiltersInterface;

final readonly class ProductSearchFilters implements ProductSearchFiltersInterface
{
    public function __construct(
        private ?string $q = null,
        private ?float $priceFrom = null,
        private ?float $priceTo = null,
        private ?int $categoryId = null,
        private ?bool $inStock = null,
        private ?float $ratingFrom = null,
        private ?string $sort = null,
        private int $perPage = 20,
    ) {}

    public function getQ(): ?string { return $this->q; }
    public function getPriceFrom(): ?float { return $this->priceFrom; }
    public function getPriceTo(): ?float { return $this->priceTo; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getInStock(): ?bool { return $this->inStock; }
    public function getRatingFrom(): ?float { return $this->ratingFrom; }
    public function getSort(): ?string { return $this->sort; }
    public function getPerPage(): int { return $this->perPage; }
}