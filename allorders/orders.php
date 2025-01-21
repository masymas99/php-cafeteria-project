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
                <li><i class="fa-solid fa-money-check"></i><a href="../allchecks/checks.php">Checks</a></li>
            </ul>
        </nav>
        <div class="vr1"></div>
    </aside>

    <main>
        <div class="container">
        <h1>All Orders</h1>

        <?php
        require('../db.php');
        // To get all users data with their orders from DB
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
                <!-- <div class="icon">
            <i class="fa-regular fa-square-check"></i>
        </div> -->

                <?php
                // To get all orders for user
                $orderQuery = "
                    SELECT o.OrderID, o.DateOrder, o.TotalPrice
                    FROM `order` o
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
                                <?php
                                // استعلام لجلب جميع المنتجات الخاصة بهذا الأوردر
                                $productQuery = "
                SELECT p.ProductName, oi.Quantity, p.ProductImage, p.ProductDescription
                FROM order_items oi
                JOIN products p ON oi.ProductID = p.ProductID
                WHERE oi.OrderID = :OrderID
                ";
                                $productStmt = $connection->prepare($productQuery);
                                $productStmt->execute([':OrderID' => $order['OrderID']]);
                                $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($products as $product): ?>
                                    <div class="product-data">
                                        <img src="../allproduct/uploads/<?php echo $product['ProductImage']; ?>" alt="Product Image"
                                            class="product-image">
                                        <div class="product-info">
                                            <p class="product-name"><?php echo htmlspecialchars($product['ProductName']); ?></p>
                                            <p class="product-quantity">Quantity: <?php echo $product['Quantity']; ?></p>
                                            <p class="product-description">Description:
                                                <?php echo $product['ProductDescription']; ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="order-total">
                                    <h4>Total: $<?php echo number_format($order['TotalPrice'], 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>


        </div>
    </main>
    <div class="login-info">

        <!-- // To get User profile Image -->
        <?php
        require('../db.php');

        $query = "SELECT ProfileImage FROM users WHERE UserID = 12";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="login-picture">
            <img src="../alluser/uploads/ <?php echo $admin['ProfileImage']; ?>" alt="Profile">
        </div>
        <h2>Admin</h2>
    </div>
</body>

</html>