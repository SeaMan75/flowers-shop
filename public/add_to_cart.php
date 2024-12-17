<?php
$servername = "MySQL-8.0";
$username = "root";
$password = "";
$dbname = "flower_shop";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $user_id = 1; // Пример, замените на реальный user_id
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Вставка данных в таблицу orders
    $sql = "INSERT INTO orders (user_id, order_date, total, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isds", $user_id, $order_date, $total_price, $status);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Вставка данных в таблицу order_details
    $sql = "INSERT INTO order_details (order_id, flower_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $order_id, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();

    echo "Товар успешно добавлен в корзину!";
}

$conn->close();
?>