<?php

declare(strict_types=1);

namespace App\DTO\Product;

use App\Validator\UniqueProductCode;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateProductDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le code produit est obligatoire')]
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Le code doit faire au moins {{ limit }} caractères',
            maxMessage: 'Le code ne peut pas dépasser {{ limit }} caractères'
        )]
        #[Assert\Regex(pattern: '/^[A-Za-z0-9\-_]+$/', message: 'Le code ne peut contenir que des lettres, chiffres, tirets et underscores')]
        #[UniqueProductCode]
        public readonly string $code,

        #[Assert\NotBlank(message: 'Le nom du produit est obligatoire')]
        #[Assert\Length(
            min: 3,
            max: 255,
            minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
            maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
        )]
        public readonly string $name,

        #[Assert\NotBlank(message: 'La catégorie est obligatoire')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'La catégorie doit faire au moins {{ limit }} caractères',
            maxMessage: 'La catégorie ne peut pas dépasser {{ limit }} caractères'
        )]
        public readonly string $category,

        #[Assert\NotBlank(message: 'Le prix est obligatoire')]
        #[Assert\PositiveOrZero(message: 'Le prix ne peut pas être négatif')]
        public readonly float $price,

        #[Assert\NotNull(message: 'La quantité est obligatoire')]
        #[Assert\PositiveOrZero(message: 'La quantité ne peut pas être négative')]
        public readonly int $quantity,

        #[Assert\Length(
            max: 5000,
            maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères'
        )]
        public readonly ?string $description = null,

        #[Assert\Length(
            max: 2048,
            maxMessage: "L'URL de l'image ne peut pas dépasser {{ limit }} caractères"
        )]
        #[Assert\Url(
            message: "L'URL de l'image n'est pas valide"
        )]
        public readonly ?string $image = null,


        #[Assert\Length(
            max: 255,
            maxMessage: 'La référence interne ne peut pas dépasser {{ limit }} caractères'
        )]
        #[Assert\Regex(
            pattern: '/^[A-Za-z0-9\-_]+$/',
            message: 'La référence interne ne peut contenir que des lettres, chiffres, tirets et underscores'
        )]
        public readonly ?string $internalReference = null,

        #[Assert\Positive(message: "L'ID externe doit être un nombre positif")]
        public readonly ?int $shellId = null,

        #[Assert\Range(
            min: 0,
            max: 5,
            notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}'
        )]
        public readonly float $rating = 0,
    ) {}
}
