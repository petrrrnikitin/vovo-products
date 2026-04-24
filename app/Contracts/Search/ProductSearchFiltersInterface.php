<?php

declare(strict_types=1);

namespace App\Contracts\Search;

interface ProductSearchFiltersInterface
{
    public function getQ(): ?string;

    public function getPriceFrom(): ?float;

    public function getPriceTo(): ?float;

    public function getCategoryId(): ?int;

    public function getInStock(): ?bool;

    public function getRatingFrom(): ?float;

    public function getSort(): ?string;

    public function getPerPage(): int;
}
