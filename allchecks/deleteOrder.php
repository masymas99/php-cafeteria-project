<?php
require('../db.php');

if (isset($_GET['order_Id'])) {
    $orderId = $_GET['order_Id'];

    try {
        // استعلام لحذف المنتج
        $query = "DELETE FROM `order` WHERE OrderID = :orderId";
        $statement = $connection->prepare($query);
        $statement->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $statement->execute();

        // إعادة التوجيه إلى صفحة المنتجات
        header('Location: checks.php');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Order ID not provided.";
}
?>