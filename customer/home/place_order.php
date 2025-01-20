<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    require('../../db.php');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_POST['ProductID']) || !isset($_POST['Quantity'])) {
        throw new Exception('Missing required data');
    }

    $userId = $_SESSION['user_id'];
    $productIds = $_POST['ProductID'];
    $quantities = $_POST['Quantity'];

    $connection->beginTransaction();

    try {
        // إنشاء الطلب الرئيسي
        $orderStmt = $connection->prepare('
            INSERT INTO `order` (UserID, DateOrder) 
            VALUES (:UserID, NOW())
        ');

        $orderStmt->execute([':UserID' => $userId]);
        $orderId = $connection->lastInsertId();

        // إضافة المنتجات إلى order_items
        $itemStmt = $connection->prepare('
            INSERT INTO order_items (OrderID, ProductID, Quantity) 
            VALUES (:OrderID, :ProductID, :Quantity)
        ');

        foreach ($productIds as $index => $productId) {
            $productId = intval($productId);
            $quantity = intval($quantities[$index]);

            if ($productId <= 0 || $quantity <= 0) {
                throw new Exception('Invalid product data');
            }

            $success = $itemStmt->execute([
                ':OrderID' => $orderId,
                ':ProductID' => $productId,
                ':Quantity' => $quantity
            ]);

            if (!$success) {
                throw new Exception('Failed to insert order item');
            }
        }

        // تحديث السعر الإجمالي
        $updateTotalStmt = $connection->prepare('
            UPDATE `order` o
            SET TotalPrice = (
                SELECT SUM(oi.Quantity * p.Price)
                FROM order_items oi
                JOIN products p ON oi.ProductID = p.ProductID
                WHERE oi.OrderID = o.OrderID
            )
            WHERE o.OrderID = :OrderID
        ');

        $updateTotalStmt->execute([':OrderID' => $orderId]);

        $connection->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully',
            'orderId' => $orderId
        ]);

    } catch (Exception $e) {
        $connection->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    error_log('Order error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 