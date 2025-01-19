<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <title>All Orders</title>
</head>
<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house"></i><a href="../adminhome/index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping"></i><a href="../allproduct/products.php">Products</a></li>
                <li><i class="fa-solid fa-users"></i><a href="../alluser/users.php">Users</a></li>
                <li><i class="fa-solid fa-receipt"></i><a href="./orders.php">Orders</a></li>
                <li><i class="fa-solid fa-money-check"></i><a href="#">Checks</a></li>
            </ul>
        </nav>
        <div class="vr1"></div>
    </aside>

    <main>
        <div class="container">
            <h1>All Orders</h1>
            
            <?php
            require_once('../adminhome/db.php');

            // استعلام لجلب جميع المستخدمين مع طلباتهم
            $query = "
                SELECT DISTINCT u.UserID, u.UserName, u.RoomNumber
                FROM users u
                JOIN `order` o ON u.UserID = o.UserID
                ORDER BY u.UserName
            ";
            
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user): ?>
                <div class="user-orders">
                    <h2>Orders for <?php echo htmlspecialchars($user['UserName']); ?></h2>
                    <p>Room Number: <?php echo htmlspecialchars($user['RoomNumber']); ?></p>
                    
                    <?php
                    // استعلام لجلب طلبات المستخدم
                    $orderQuery = "
                        SELECT o.OrderID, o.DateOrder, o.TotalPrice,
                               GROUP_CONCAT(p.ProductName, ' (', oi.Quantity, ')' SEPARATOR ', ') as Products
                        FROM `order` o
                        JOIN order_items oi ON o.OrderID = oi.OrderID
                        JOIN products p ON oi.ProductID = p.ProductID
                        WHERE o.UserID = :UserID
                        GROUP BY o.OrderID
                        ORDER BY o.DateOrder DESC
                    ";
                    
                    $orderStmt = $connection->prepare($orderQuery);
                    $orderStmt->execute([':UserID' => $user['UserID']]);
                    $orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="orders-container">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <h3>Order #<?php echo $order['OrderID']; ?></h3>
                                    <span class="order-date"><?php echo date('Y-m-d H:i', strtotime($order['DateOrder'])); ?></span>
                                </div>
                                <div class="order-products">
                                    <p><?php echo htmlspecialchars($order['Products']); ?></p>
                                </div>
                                <div class="order-total">
                                    <h4>Total: $<?php echo number_format($order['TotalPrice'], 2); ?></h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html> 