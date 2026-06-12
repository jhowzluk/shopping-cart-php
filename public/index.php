<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Enum\PaymentMethod;
use App\Exception\InsufficientStockException;
use App\Model\Cart;
use App\Model\Product;
use App\Service\CartService;

$products = [
    1 => new Product('T-Shirt', 49.90, 5),
    2 => new Product('Jeans', 129.90, 3),
    3 => new Product('Sneakers', 199.90, 2),
];

$cart = new Cart();
$service = new CartService();

while (true) {
    echo "\n--- CART ---\n";
    $items = $cart->getItems();
    if (empty($items)) {
        echo "  (empty)\n";
    } else {
        foreach ($items as $item) {
            echo "  - {$item['product']->getName()} x{$item['quantity']} = $" . number_format($item['product']->getPrice() * $item['quantity'], 2) . "\n";
        }
        echo "  Total: $" . number_format($cart->getTotal(), 2) . "\n";
    }

    echo "\n--- MENU ---\n";
    echo "1. List products\n";
    echo "2. Add to cart\n";
    echo "3. Remove from cart\n";
    echo "4. Checkout\n";
    echo "0. Exit\n";
    echo "Option: ";

    $option = trim(fgets(STDIN));

    match ($option) {
        '1' => listProducts($products),
        '2' => addToCart($products, $cart),
        '3' => removeFromCart($products, $cart),
        '4' => checkout($cart, $service),
        '0' => exit("Bye!\n"),
        default => print("Invalid option.\n"),
    };
}

function listProducts(array $products): void
{
    echo "\nAvailable products:\n";
    foreach ($products as $id => $product) {
        echo "  {$id}. {$product->getName()} - $" . number_format($product->getPrice(), 2) . " (stock: {$product->getStock()})\n";
    }
}

function addToCart(array $products, Cart $cart): void
{
    echo "Product ID: ";
    $id = (int) trim(fgets(STDIN));

    if (!isset($products[$id])) {
        echo "Product not found.\n";
        return;
    }

    echo "Quantity: ";
    $qty = (int) trim(fgets(STDIN));

    try {
        $cart->add($products[$id], $qty);
        echo "Added!\n";
    } catch (InsufficientStockException $e) {
        echo "Error: {$e->getMessage()}\n";
    }
}

function removeFromCart(array $products, Cart $cart): void
{
    echo "Product ID to remove: ";
    $id = (int) trim(fgets(STDIN));

    if (!isset($products[$id])) {
        echo "Product not found.\n";
        return;
    }

    echo "Quantity: ";
    $qty = (int) trim(fgets(STDIN));

    $cart->remove($products[$id], $qty);
    echo "Removed!\n";
}

function checkout(Cart $cart, CartService $service): void
{
    $total = $cart->getTotal();

    if ($total === 0.0) {
        echo "Cart is empty.\n";
        return;
    }

    echo "\nTotal: $" . number_format($total, 2) . "\n";
    echo "With Pix: $" . number_format($service->calculateTotalWithDiscount($cart, PaymentMethod::PIX), 2) . "\n";
    echo "With Credit Card: $" . number_format($service->calculateTotalWithDiscount($cart, PaymentMethod::CREDIT_CARD), 2) . "\n";
    echo "With Boleto: $" . number_format($service->calculateTotalWithDiscount($cart, PaymentMethod::BOLETO), 2) . "\n";
}
