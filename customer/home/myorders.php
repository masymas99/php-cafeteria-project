<?php

require_once('../../db.php');

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: ../php-project/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // جلب الطلبات للمستخدم
    $orderQuery = $connection->prepare('
        SELECT o.OrderID, o.DateOrder, o.TotalPrice 
        FROM `order` o
        WHERE o.UserID = :UserID
        ORDER BY o.DateOrder DESC
    ');
    $orderQuery->execute([':UserID' => $userId]);
    $orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);

    // جلب تفاصيل العناصر لكل طلب
    $orderDetails = [];
    foreach ($orders as $order) {
        $orderId = $order['OrderID'];

        $itemQuery = $connection->prepare('
        SELECT 
            oi.OrderItemID, 
            oi.ProductID, 
            p.ProductName AS ProductName, 
            p.Price, 
            p.ProductImage AS ProductImage, 
            oi.Quantity, 
            (p.Price * oi.Quantity) AS SubTotal
        FROM order_items oi
        JOIN products p ON oi.ProductID = p.ProductID
        WHERE oi.OrderID = :OrderID
    ');

        $itemQuery->execute([':OrderID' => $orderId]);
        $items = $itemQuery->fetchAll(PDO::FETCH_ASSOC);

        $orderDetails[] = [
            'order' => $order,
            'items' => $items
        ];
    }
} catch (Exception $e) {
    die('Error fetching orders: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="myorder.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>My Orders</title>

</head>
<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house"></i><a href="../home/userhome.php">Home </a></li>
                <li><i class="fa-solid fa-receipt"></i><a href="#">My Orders</a></li>
                <li><i class="fa-solid fa-right-from-bracket"></i><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>
    <div class="vr2"></div>
    <hr>
    <main>
        <h1>My Orders</h1>
        <div class="orders-container">
            <?php foreach ($orderDetails as $orderDetail): ?>
                <div class="order-card">
                    <div class="orderinfo">
                    <h3>Order #<?php echo $orderDetail['order']['OrderID']; ?></h3>
                    <p>Date: <?php echo date('Y-m-d H:i', strtotime($orderDetail['order']['DateOrder'])); ?></p>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($orderDetail['items'] as $item): ?>
                            <div class="item">
                                <img src="../../allproduct/uploads/<?php echo htmlspecialchars($item['ProductImage']); ?>" alt="<?php echo htmlspecialchars($item['ProductName']); ?>">
                                <h4><?php echo htmlspecialchars($item['ProductName']); ?></h4>
                                <p>Quantity: <?php echo htmlspecialchars($item['Quantity']); ?></p>
                                <p>Total: $<?php echo number_format($item['SubTotal'], 2); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <h4>Total Price: $<?php echo number_format($orderDetail['order']['TotalPrice'], 2); ?></h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total Price for all orders -->
        <div class="total-price">
            <h2>Total Orders price : $<?php echo number_format(array_sum(array_column($orders, 'TotalPrice')), 2); ?></h2>
        </div>
    </main>
    
    <div class="login-info">
        <div class="login-picture">
            <img src="../../alluser/uploads/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="User Profile Picture" width="50" height="50" style="border-radius: 50%; object-fit: cover;">
        </div>
        
        <h2> <?php echo ($_SESSION['username']); ?></h2> 
    </div>
</body>
</html>
