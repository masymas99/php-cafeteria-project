<?php
require('../db.php');

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    try {
        // استعلام لحذف المنتج
        $query = "DELETE FROM products WHERE ProductID = :productId";
        $statement = $connection->prepare($query);
        $statement->bindParam(':productId', $productId, PDO::PARAM_INT);
        $statement->execute();

        // إعادة التوجيه إلى صفحة المنتجات
        header('Location: Products.php');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Product ID not provided.";
}
?>