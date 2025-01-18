<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once('db.php');
    
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
        // إنشاء الطلب الرئيسي
        $orderStmt = $connection->prepare('
            INSERT INTO `order` (UserID, ProductID, Quantity) 
            VALUES (:UserID, :ProductID, :Quantity)
        ');

        // إدخال كل منتج
        foreach ($productIds as $index => $productId) {
            $productId = intval($productId);
            $quantity = intval($quantities[$index]);

            if ($productId <= 0 || $quantity <= 0) {
                throw new Exception('Invalid product data');
            }

            $success = $orderStmt->execute([
                ':UserID' => $userId,
                ':ProductID' => $productId,
                ':Quantity' => $quantity
            ]);

            if (!$success) {
                throw new Exception('Failed to insert order');
            }
        }

        $connection->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Orders placed successfully'
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