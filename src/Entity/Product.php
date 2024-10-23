<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\InventoryStatus;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['code'], message: 'Ce code produit existe déjà')]
class Product
{
    /**
     * Seuil en dessous duquel le stock est considéré comme faible
     */
    public const LOW_STOCK_THRESHOLD = 5;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le code produit est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le code doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le code ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-z0-9\-_]+$/',
        message: 'Le code ne peut contenir que des lettres, chiffres, tirets et underscores'
    )]
    #[Groups(['read', 'write'])]
    private string $code = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Groups(['read', 'write'])]
    private string $name = '';

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 5000,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Groups(['read', 'write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 2048,
        maxMessage: "L'URL de l'image ne peut pas dépasser {{ limit }} caractères"
    )]
    #[Assert\Url(
        message: "L'URL de l'image n'est pas valide"
    )]
    #[Groups(['read', 'write'])]
    private ?string $image = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La catégorie est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'La catégorie doit faire au moins {{ limit }} caractères',
        maxMessage: 'La catégorie ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Groups(['read', 'write'])]
    private string $category = '';

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'Le prix est obligatoire')]
    #[Assert\PositiveOrZero(message: 'Le prix ne peut pas être négatif')]
    #[Groups(['read', 'write'])]
    private float $price = 0.00;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La quantité est obligatoire')]
    #[Assert\PositiveOrZero(message: 'La quantité ne peut pas être négative')]
    #[Groups(['read', 'write'])]
    private int $quantity = 0;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La référence interne ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-z0-9\-_]+$/',
        message: 'La référence interne ne peut contenir que des lettres, chiffres, tirets et underscores'
    )]
    #[Groups(['read', 'write'])]
    private ?string $internalReference = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "L'ID externe doit être un nombre positif")]
    #[Groups(['read', 'write'])]
    private ?int $shellId = null;

    #[ORM\Column(type: 'string', enumType: InventoryStatus::class)]
    #[Groups(['read'])]
    private InventoryStatus $inventoryStatus = InventoryStatus::OUTOFSTOCK;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}'
    )]
    #[Groups(['read', 'write'])]
    private float $rating = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['read'])]
    private \DateTimeImmutable $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = strtoupper(trim($code));

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $this->normalizeString($name);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description ? $this->normalizeString($description) : null;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $this->normalizeString($category);

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Met à jour automatiquement le statut de stock en fonction de la quantité
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        $this->updateStockStatus();
        return $this;
    }

    public function getInternalReference(): ?string
    {
        return $this->internalReference;
    }

    public function setInternalReference(?string $internalReference): self
    {
        $this->internalReference = $internalReference;

        return $this;
    }

    public function getShellId(): ?int
    {
        return $this->shellId;
    }

    public function setShellId(?int $shellId): self
    {
        $this->shellId = $shellId;

        return $this;
    }

    public function getInventoryStatus(): InventoryStatus
    {
        return $this->inventoryStatus;
    }

    private function updateStockStatus(): void
    {
        $this->inventoryStatus = InventoryStatus::fromQuantity(
            quantity: $this->quantity,
            threshold: self::LOW_STOCK_THRESHOLD
        );
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function checkStockStatus(): void
    {
        $this->updateStockStatus();
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    private function normalizeString(string $value): string
    {
        return mb_strtolower((string)preg_replace('/\s+/', ' ', trim($value)));
    }
}
