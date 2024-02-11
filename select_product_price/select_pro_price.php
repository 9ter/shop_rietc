<?php

header('Content-Type: application/json; charset=utf-8');
include("../config.php");

if (isset($_POST["json"], $_POST["shop_name"])) {
    $json = $_POST["json"];
    $menu_json = json_decode($json, JSON_UNESCAPED_UNICODE);
    $shop_name = $_POST["shop_name"];

    $price = 0;
    $data = [];

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT price FROM product WHERE product_name = ? AND shop_name = ?");
    $stmt->bind_param("ss", $item, $shop_name);

    foreach ($menu_json as $item) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data[] = $result->fetch_assoc()['price'];
        }
    }

    $stmt->close();

    $price = array_sum($data);

    echo $price;
}
?>