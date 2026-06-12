<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\PaymentMethod;
use App\Model\Cart;

class CartService
{
    public function calculateTotalWithDiscount(Cart $cart, PaymentMethod $method): float
    {
        $total = $cart->getTotal();
        $discount = $method->discountPercentage();

        return $total - ($total * $discount);
    }
}
