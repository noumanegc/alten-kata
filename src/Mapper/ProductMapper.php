<?php

declare(strict_types=1);

namespace App\Mapper;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Entity\Product;

final class ProductMapper
{
    public function createProductFromDTO(CreateProductDTO $dto): Product
    {
        $product = new Product();
        $product->setCode($dto->code);
        $product->setName($dto->name);
        $product->setCategory($dto->category);
        $product->setPrice($dto->price);
        $product->setQuantity($dto->quantity);
        $product->setDescription($dto->description);
        $product->setImage($dto->image);
        $product->setInternalReference($dto->internalReference);
        $product->setShellId($dto->shellId);
        $product->setRating($dto->rating);

        return $product;
    }

    public function updateProductFromDTO(Product $product, UpdateProductDTO $dto): void
    {
        if ($dto->code !== null) {
            $product->setCode($dto->code);
        }
        if ($dto->name !== null) {
            $product->setName($dto->name);
        }
        if ($dto->category !== null) {
            $product->setCategory($dto->category);
        }
        if ($dto->price !== null) {
            $product->setPrice($dto->price);
        }
        if ($dto->quantity !== null) {
            $product->setQuantity($dto->quantity);
        }
        if ($dto->description !== null) {
            $product->setDescription($dto->description);
        }
        if ($dto->image !== null) {
            $product->setImage($dto->image);
        }
        if ($dto->internalReference !== null) {
            $product->setInternalReference($dto->internalReference);
        }
        if ($dto->shellId !== null) {
            $product->setShellId($dto->shellId);
        }
        if ($dto->rating !== null) {
            $product->setRating($dto->rating);
        }
    }
}
