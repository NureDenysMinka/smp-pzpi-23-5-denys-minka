<?php

class AgeLimits {
    public const MIN = 7;
    public const MAX = 150;
}

function formatName(string $name): string {
    $padding = 20 - mb_strlen($name);
    return $padding > 0 ? $name . str_repeat(" ", $padding) : $name;
}

class GoodsCatalog {
    private const FILE_PATH = './data/goods.json';
    private array $goods;

    public function __construct() {
        $this->goods = $this->load();
    }

    public function getAll(): array {
        return $this->goods;
    }

    public function findById(int $id): ?array {
        return array_find($this->goods, fn($item) => $item['id'] === $id);
    }

    public function findByName(string $name): ?array {
        return array_find($this->goods, fn($item) => $item['name'] === $name);
    }

    private function load(): array {
        $json = file_get_contents(self::FILE_PATH);
        return json_decode($json, true);
    }
}

class ShoppingCart {
    private array $items = [];

    public function isEmpty(): bool {
        return empty($this->items);
    }

    public function all(): array {
        return $this->items;
    }

    public function add(string $name, int $quantity): void {
        if ($quantity < 0 || $quantity >= 100) return;

        if ($quantity === 0) {
            $this->remove($name);
        } else {
            $this->items[$name] = $quantity;
        }
    }

    public function remove(string $name): void {
        unset($this->items[$name]);
    }

    public function total(GoodsCatalog $catalog): int {
        $sum = 0;
        foreach ($this->items as $name => $qty) {
            $good = $catalog->findByName($name);
            if ($good) {
                $sum += $good['price'] * $qty;
            }
        }
        return $sum;
    }
}

class UserProfile {
    private string $name = '';
    private int $age = 0;

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setAge(int $age): void {
        $this->age = $age;
    }
}

class App {
    private GoodsCatalog $catalog;
    private ShoppingCart $cart;
    private UserProfile $user;

    public function __construct() {
        $this->catalog = new GoodsCatalog();
        $this->cart = new ShoppingCart();
        $this->user = new UserProfile();
    }

    public function run(): void {
        while (true) {
            $this->showMainMenu();
            $choice = $this->input();

            match ($choice) {
                "1" => $this->handleSelection(),
                "2" => $this->showReceipt(),
                "3" => $this->setupUser(),
                "0" => $this->exitApp(),
                default => print("ПОМИЛКА! Введіть правильну команду\n")
            };
        }
    }

    private function showMainMenu(): void {
        echo <<<MENU

################################
# ПРОДОВОЛЬЧИЙ МАГАЗИН "ВЕСНА" #
################################
1 Вибрати товари
2 Отримати підсумковий рахунок
3 Налаштувати свій профіль
0 Вийти з програми
Введіть команду:
MENU;
    }

    private function handleSelection(): void {
        while (true) {
            $this->showGoods();

            $id = $this->input();
            if ($id === "0") return;

            $good = $this->catalog->findById((int)$id);
            if (!$good) {
                echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
                continue;
            }

            echo "Вибрано: {$good['name']}\nВведіть кількість, штук: ";
            $qty = $this->input();

            if (!ctype_digit($qty)) {
                echo "ПОМИЛКА! Кількість повинна бути цілим невід'ємним числом.\n";
                continue;
            }

            $this->cart->add($good['name'], (int)$qty);
            $this->showCart();
        }
    }

    private function showGoods(): void {
        echo "№  НАЗВА                   ЦІНА\n";

        foreach ($this->catalog->getAll() as $item) {
            printf("%-2s %-24s %5s\n", $item['id'], formatName($item['name']), $item['price']);
        }

        echo "   -----------\n0  ПОВЕРНУТИСЯ\nВиберіть товар: ";
    }

    private function showReceipt(): void {
        if ($this->cart->isEmpty()) {
            echo "КОШИK ПОРОЖНІЙ\n";
            return;
        }

        echo "№  НАЗВА                   ЦІНА   КІЛЬКІСТЬ  ВАРТІСТЬ\n";
        echo "---------------------------------\n";

        $i = 1;
        foreach ($this->cart->all() as $name => $qty) {
            $good = $this->catalog->findByName($name);
            $sum = $good['price'] * $qty;
            printf("%-2d %-24s %6d %9d %9d\n", $i++, formatName($name), $good['price'], $qty, $sum);
        }

        echo "---------------------------------\n";
        printf("РАЗОМ ДО СПЛАТИ: %d\n", $this->cart->total($this->catalog));
    }

    private function showCart(): void {
        if ($this->cart->isEmpty()) {
            echo "КОШИK ПОРОЖНІЙ\n";
            return;
        }

        echo "У КОШИКУ:\nНАЗВА                   КІЛЬКІСТЬ\n";
        echo "---------------------------------\n";

        foreach ($this->cart->all() as $name => $qty) {
            printf("%-24s %5d\n", formatName($name), $qty);
        }

        echo "---------------------------------\n";
    }

    private function setupUser(): void {
        do {
            echo "Ваше імʼя: ";
            $name = $this->input();
        } while (!preg_match("/^\p{L}+$/u", $name));

        $this->user->setName($name);

        do {
            echo "Ваш вік: ";
            $ageInput = $this->input();
            $ageValid = ctype_digit($ageInput) && $ageInput >= AgeLimits::MIN && $ageInput <= AgeLimits::MAX;
        } while (!$ageValid);

        $this->user->setAge((int)$ageInput);
    }

    private function exitApp(): void {
        echo "Дякуємо за покупку в магазині \"Весна\"! До побачення!\n";
        exit;
    }

    private function input(): string {
        $line = fgets(STDIN);
        return $line ? trim($line) : '';
    }
}

$app = new App();
$app->run();
