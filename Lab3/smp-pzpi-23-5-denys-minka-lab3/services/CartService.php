<?php

require_once './services/ProductsService.php';

class CartService
{
	private ProductsService $productsService;

	public function __construct()
	{
		$this->productsService = new ProductsService();
	}

	// Отримати всі товари з кошика
	public function getCartItems(): array {
		$products = $this->productsService->getProducts();
		$cart = [];

		foreach ($products as $product) {
			if (isset($_SESSION['cart'][$product['id']])) {
				$cart[] = [
					'id' => $product['id'],
					'name' => $product['name'],
					'price' => $product['price'],
					'quantity' => $_SESSION['cart'][$product['id']],
					'total' => $product['price'] * $_SESSION['cart'][$product['id']],
				];
			}
		}

		return $cart;
	}

	// Підрахунок загальної вартості
	public function calculateTotal(): int {
		$total = 0;

		foreach ($this->getCartItems() as $item) {
			$total += $item['total'];
		}

		return $total;
	}

	// Додавання товарів до кошика
	public function addItem(array $quantities): void {
		$isEmpty = true;

		foreach ($quantities as $id => $quantity) {
			$id = (int) $id;
			$quantity = max(0, min(100, (int) $quantity)); // Обмеження: 0–100

			if ($quantity > 0) {
				$_SESSION['cart'][$id] = $quantity;
				$isEmpty = false;
			}
		}

		if (!$isEmpty) {
			header("Location: /cart");
			exit;
		}
	}

	// Видалення одного товару з кошика
	public function removeItem(int $id): void {
		unset($_SESSION['cart'][$id]);
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit;
	}

	// Повне очищення кошика
	public function clearCart(): void {
		unset($_SESSION['cart']);
		header("Location: /");
		exit;
	}
}
