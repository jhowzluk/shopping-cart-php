<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\InsufficientStockException;

class Product
{
    public function __construct(
        private string $name,
        private float  $price,
        private int    $stock
    )
    {
    }

    public function decreaseStock(int $quantity): void
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
        } else {
            throw new InsufficientStockException("Insufficient stock!");
        }
    }

    public function increaseStock(int $quantity): void
    {
        $this->stock += $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
