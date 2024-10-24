<?php

declare(strict_types=1);

namespace App\Enum;

enum InventoryStatus: string
{
    case INSTOCK = 'INSTOCK';
    case LOWSTOCK = 'LOWSTOCK';
    case OUTOFSTOCK = 'OUTOFSTOCK';

    public static function fromQuantity(int $quantity, int $threshold = 5): self
    {
        if ($quantity <= 0) {
            return self::OUTOFSTOCK;
        }

        if ($quantity <= $threshold) {
            return self::LOWSTOCK;
        }

        return self::INSTOCK;
    }

    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
