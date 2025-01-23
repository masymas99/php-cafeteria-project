<?php
require('../db.php');

if (isset($_GET['order_Id'])) {
    $orderId = $_GET['order_Id'];

    try {
        $query = "DELETE FROM `order` WHERE OrderID = :orderId";
        $statement = $connection->prepare($query);
        $statement->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $statement->execute();

        header('Location: checks.php');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Order ID not provided.";
}
?>