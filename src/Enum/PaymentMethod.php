<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentMethod: string
{
    case PIX = 'pix';
    case CREDIT_CARD = 'credit_card';
    case BOLETO = 'boleto';

    public function discountPercentage(): float
    {
        return match ($this) {
            self::PIX => 0.1,
            self::CREDIT_CARD => 0.05,
            self::BOLETO => 0.02,
        };
    }
}
