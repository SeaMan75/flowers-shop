<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Пожалуйста, войдите в систему, чтобы увидеть содержимое корзины.";
    exit;
}

$user_id = $_SESSION['user_id'];
$servername = "MySQL-8.0";
$username = "root";
$password = "";
$dbname = "flower_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT flowers.name, quantity, flowers.price 
FROM orders 
JOIN flowers ON orders.flower_id = flowers.flower_id 
WHERE orders.user_id = ? AND orders.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0;

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>Товар</th><th>Количество</th><th>Цена за единицу</th><th>Стоимость</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        $itemTotal = $row['quantity'] * $row['price'];
        $totalPrice += $itemTotal;
        echo "<tr><td>" . $row['name'] . "</td><td>" . $row['quantity'] . "</td><td>" . $row['price'] . " руб.</td><td>" . $itemTotal . " руб.</td></tr>";
    }
    echo "</tbody></table>";
    echo "<div class='text-right'><strong>Итоговая стоимость: " . $totalPrice . " руб.</strong></div>";

    // Добавляем способы доставки
    echo "<div class='form-group'>";
    echo "<label for='deliveryMethod'>Способ доставки:</label>";
    echo "<select class='form-control' id='deliveryMethod' name='deliveryMethod'>";
    echo "<option value='courier'>Курьер</option>";
    echo "<option value='pickup'>Самовывоз</option>";
    echo "<option value='post'>Почта</option>";
    echo "</select>";
    echo "</div>";

    // Кнопка оплаты
    echo "<button type='button' class='btn btn-primary' id='checkoutButton'>Оплатить</button>";
} else {
    echo "Ваша корзина пуста.";
}

$stmt->close();
$conn->close();
?>