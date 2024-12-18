<?php
session_start();
$servername = "MySQL-8.0";
$username = "root";
$password = "";
$dbname = "flower_shop";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Проверка пользователя в базе данных
    $sql = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Успешная авторизация

            $delete_sql = "DELETE FROM orders WHERE user_id = ? AND status = 'pending'";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            $_SESSION['user_id'] = $id;
            echo "Добро пожаловать, " . $email . "!";
        } else {
            // Неверный пароль
            echo "Неверный пароль. Попробуйте снова.";
        }
    } else {
        // Пользователь не найден
        echo "Пользователь с таким логином не найден.";
    }
    $stmt->close();
}
$conn->close();
?>