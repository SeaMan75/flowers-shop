<?php
session_start();
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
    $flower_id = $_POST['flower_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Вставка данных в таблицу orders
    $sql = "INSERT INTO orders (user_id, order_date, total, status, flower_id, quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdsii", $user_id, $order_date, $total_price, $status, $flower_id, $quantity);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    echo "Товар успешно добавлен в корзину!";
}

$conn->close();
?>