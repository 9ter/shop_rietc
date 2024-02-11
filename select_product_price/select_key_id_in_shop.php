<?php
header('Content-Type: application/json; charset=utf-8');
include("../config.php");

if (isset($_POST["id_in_shop"])) {
    $id_in_shop = $_POST["id_in_shop"];

    $sql = "SELECT * FROM product WHERE id_in_shop = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_in_shop);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc(); // Use fetch_assoc instead of fetch_all
        //$json = json_encode($data["product_name"], JSON_UNESCAPED_UNICODE);
        echo $data["product_name"];
    } else {
        echo 0;
    }
}


?>