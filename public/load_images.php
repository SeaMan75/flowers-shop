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

    $sql = "SELECT name, image FROM flowers";
    $result = $conn->query($sql);

    $flowers = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $flowers[] = $row;
            }
        }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($flowers);
?>