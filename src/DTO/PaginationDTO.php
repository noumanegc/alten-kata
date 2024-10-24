<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationDTO
{
    public function __construct(
        #[Assert\PositiveOrZero(message: 'La page doit être un nombre positif')]
        public readonly ?int $page = null,

        #[Assert\PositiveOrZero(message: 'La limite doit être un nombre positif')]
        #[Assert\LessThanOrEqual(100, message: 'La limite ne peut pas dépasser 100 éléments')]
        public readonly ?int $limit = null,
    ) {}

    public function getPage(): int
    {
        return $this->page ?? 1;
    }

    public function getLimit(): int
    {
        return $this->limit ?? 10;
    }
}
