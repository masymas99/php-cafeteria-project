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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>My Orders</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background-color: #f9f9f9;
}

aside {
    position: fixed;
    width: 200px;
    height: 100%;
    background-color: #fff;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

aside h1 {
    font-size: 20px;
    font-weight: 700;
    color: #9B4B72;
    margin-bottom: 30px;
}

aside nav ul {
    list-style: none;
    padding: 0;
}

aside nav ul li {
    margin: 15px;
}

aside nav ul li a {
    text-decoration: none;
    font-size: 16px;
    color: #333;
}

aside nav ul li a i {
    width: 25px;
    color: #666;
}

aside nav ul li a:hover {
    color:rgb(206, 197, 201);
}

main {
    padding: 30px;
    margin-left: 0px;
}

.orders-container {
    display: block;
    gap: 20px;
}

.order-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 100%; /* عرض البطاقة بالكامل */
    box-sizing: border-box;
    margin-bottom: 30px;
}

.order-card h2 {
    font-size: 18px;
    color: #9B4B72;
    margin: 0 0 10px 0;
}

.order-card p {
    margin: 10px 0;
    font-size: 14px;
    color: #3F813C;
}

.order-items {
    display: flex;
    flex-wrap: wrap; 
    gap: 30px;
    margin-top: 15px;
}

.order-item {
    display: flex;
    flex-direction: column;
     width:60%;  
    padding: 20px;
    border-right: 2px solid #ddd; 
    box-sizing: border-box;
}

.order-items .order-item:last-child {
    border-right: none;
}

.order-item img {
  
    width: 40%;
    height: 200px;
    /* object-fit: cover; */
    border-radius: 30px;
}

.order-item h4 {
    font-size: 16px;
    color: #9B4B72;
}

.order-item p {
    margin: 5px 0;
    font-size: 14px;
    color: #333;
}

.cancel-btn:hover {
    background-color: rgb(182, 68, 123);
}

.total-price {
    text-align: center;
    font-size: 20px;
    font-weight: 700;
    color: #5cb85c;
    margin-top: 20px;
}


    </style>
</head>
<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house"></i><a href="../home/userhome.php">Home page</a></li>
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
                    <h3>Order #<?php echo $orderDetail['order']['OrderID']; ?></h3>
                    <p>Date: <?php echo date('Y-m-d H:i', strtotime($orderDetail['order']['DateOrder'])); ?></p>
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
                        <h4>Total Order Price: $<?php echo number_format($orderDetail['order']['TotalPrice'], 2); ?></h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total Price for all orders -->
        <div class="total-price">
            <h2>Total Price for All Orders: $<?php echo number_format(array_sum(array_column($orders, 'TotalPrice')), 2); ?></h2>
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
