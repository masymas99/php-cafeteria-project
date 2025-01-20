<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once('../../db.php');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_POST['UserID']) || !isset($_POST['ProductID']) || !isset($_POST['Quantity'])) {
        throw new Exception('Missing required data');
    }

    $userId = intval($_POST['UserID']);
    $productIds = $_POST['ProductID'];
    $quantities = $_POST['Quantity'];

    if ($userId <= 0) {
        throw new Exception('Invalid user ID');
    }

    // بدء المعاملة
    $connection->beginTransaction();

    try {
        // إنشاء الطلب الرئيسي أولاً في جدول order
        $orderStmt = $connection->prepare('
            INSERT INTO `order` (UserID, DateOrder) 
            VALUES (:UserID, NOW())
        ');

        $orderStmt->execute([':UserID' => $userId]);
        $orderId = $connection->lastInsertId();

        // إضافة المنتجات إلى جدول order_items
        $itemStmt = $connection->prepare('
            INSERT INTO order_items (OrderID, ProductID, Quantity) 
            VALUES (:OrderID, :ProductID, :Quantity)
        ');

        // إضافة كل منتج إلى الطلب
        foreach ($productIds as $index => $productId) {
            $productId = intval($productId);
            $quantity = intval($quantities[$index]);

            if ($productId <= 0 || $quantity <= 0) {
                throw new Exception('Invalid product data');
            }

            // إضافة المنتج إلى order_items
            $success = $itemStmt->execute([
                ':OrderID' => $orderId,
                ':ProductID' => $productId,
                ':Quantity' => $quantity
            ]);

            if (!$success) {
                throw new Exception('Failed to insert order item');
            }
        }

        // حساب وتحديث السعر الإجمالي للطلب
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
    error_log('Checkout error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>