<?php

declare(strict_types=1);

namespace App\Model;

class Cart
{
    private array $items = [];

    public function add(Product $product, int $quantity = 1): void
    {
        $product->decreaseStock($quantity);

        foreach ($this->items as &$item) {
            if ($item['product'] === $product) {
                $item['quantity'] += $quantity;
                return;
            }
        }

        $this->items[] = ['product' => $product, 'quantity' => $quantity];
    }

    public function remove(Product $product, int $quantity = 1): void
    {
        foreach ($this->items as $key => &$item) {
            if ($item['product'] === $product) {
                $item['quantity'] -= $quantity;
                $product->increaseStock($quantity);

                if ($item['quantity'] <= 0) {
                    unset($this->items[$key]);
                }
                return;
            }
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return array_reduce($this->items, fn($total, $item) =>
            $total + ($item['product']->getPrice() * $item['quantity']), 0.0);
    }
}
