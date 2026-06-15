# Shopping Cart

A simple shopping cart system built in PHP with OOP, enums, and custom exception handling.

## Features

- Product management with stock control
- Cart with add/remove items
- Total calculation with discount by payment method (Pix, Credit Card, Boleto)
- Custom exception for insufficient stock

## Project Structure

```
public/
└── index.php              # CLI entry point
src/
├── Enum/
│   └── PaymentMethod.php
├── Exception/
│   └── InsufficientStockException.php
├── Model/
│   ├── Product.php
│   └── Cart.php
└── Service/
    └── CartService.php
```

## How to Run

```bash
composer dump-autoload
php public/index.php
```

Requires PHP 8.1+.
