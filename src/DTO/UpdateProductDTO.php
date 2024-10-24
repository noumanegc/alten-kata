<?php

declare(strict_types=1);

namespace App\DTO\Product;

use App\Validator\UniqueProductCode;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductDTO
{
    public function __construct(
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Le code doit faire au moins {{ limit }} caractères',
            maxMessage: 'Le code ne peut pas dépasser {{ limit }} caractères'
        )]
        #[Assert\Regex(pattern: '/^[A-Za-z0-9\-_]+$/', message: 'Le code ne peut contenir que des lettres, chiffres, tirets et underscores')]
        #[UniqueProductCode]
        public readonly ?string $code = null,

        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
            maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
        )]
        public readonly ?string $name = null,

        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'La catégorie doit faire au moins {{ limit }} caractères',
            maxMessage: 'La catégorie ne peut pas dépasser {{ limit }} caractères'
        )]
        public readonly ?string $category = null,

        #[Assert\PositiveOrZero]
        public readonly ?float $price = null,

        #[Assert\PositiveOrZero]
        public readonly ?int $quantity = null,

        #[Assert\Length(max: 5000)]
        public readonly ?string $description = null,

        #[Assert\Length(max: 2048)]
        #[Assert\Url]
        public readonly ?string $image = null,

        #[Assert\Length(max: 255)]
        #[Assert\Regex(pattern: '/^[A-Za-z0-9\-_]+$/')]
        public readonly ?string $internalReference = null,

        #[Assert\Positive]
        public readonly ?int $shellId = null,

        #[Assert\Range(min: 0, max: 5)]
        public readonly ?float $rating = null,
    ) {}
}
