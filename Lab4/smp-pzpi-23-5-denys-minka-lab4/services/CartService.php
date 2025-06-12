<?php

require_once './services/ProductsService.php';
class CartService
{
	private ProductsService $productsService;

	public function __construct()
	{
		$this->productsService = new ProductsService();
	}

	public function getCartItems(): array {
		$products = $this->productsService->getProducts();
		$cart = [];

		foreach ($products as $product) {
			if (isset($_SESSION['cart'][$product['id']])) {
				$item = [
					'id' => $product['id'],
					'name' => $product['name'],
					'price' => $product['price'],
					'quantity' => $_SESSION['cart'][$product['id']],
					'total' => $product['price'] * $_SESSION['cart'][$product['id']]
				];

				$cart[] = $item;
			}
		}

		return $cart;
	}

	public function calculateTotal(): int {
		$total = 0;

		foreach ($this->getCartItems() as $item) {
			$total += $item['total'];
		}

		return $total;
	}

	public function addItem(array $quantities): void {
		$isEmpty = true;

		foreach ($quantities as $id => $quantity) {
			$id = (int) $id;
			$quantity = max(0, min(100, (int) $quantity));

			if ($quantity > 0) {
				$_SESSION['cart'][$id] = $quantity;
				$isEmpty = false;
			}
		}

		if (!$isEmpty) {
			header("Location: " . "/cart");
		}
	}

	public function removeItem(int $id): void {
		unset($_SESSION['cart'][$id]);
		header("Location: " . $_SERVER['REQUEST_URI']);
	}

	public function clearCart(): void {
		unset($_SESSION['cart']);
		header("Location: " . "/");
	}
}
