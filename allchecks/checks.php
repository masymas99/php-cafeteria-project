<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <title>Checks</title>
</head>
<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house"></i><a href="../adminhome/index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping"></i><a href="../allproduct/products.php">Products</a></li>
                <li><i class="fa-solid fa-users"></i><a href="../alluser/users.php">Users</a></li>
                <li><i class="fa-solid fa-receipt"></i><a href="../allorders/orders.php">Orders</a></li>
                <li><i class="fa-solid fa-money-check"></i><a href="./checks.php">Checks</a></li>
            </ul>
        </nav>
        <div class="vr1"></div>
    </aside>

    <main>
        <div class="container">
            <h1>Checks</h1>
            
            <!-- فلاتر البحث -->
            <div class="filters">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <label for="user">User:</label>
                        <select name="user" id="user">
                            <option value="">All Users</option>
                            <?php
                            require_once('../adminhome/db.php');
                            $userQuery = "SELECT DISTINCT u.UserID, u.UserName FROM users u JOIN `order` o ON u.UserID = o.UserID ORDER BY u.UserName";
                            $userStmt = $connection->query($userQuery);
                            while ($user = $userStmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_GET['user']) && $_GET['user'] == $user['UserID']) ? 'selected' : '';
                                echo "<option value='{$user['UserID']}' {$selected}>{$user['UserName']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date_from">From:</label>
                        <input type="date" name="date_from" id="date_from" 
                               value="<?php echo $_GET['date_from'] ?? ''; ?>">
                    </div>

                    <div class="filter-group">
                        <label for="date_to">To:</label>
                        <input type="date" name="date_to" id="date_to"
                               value="<?php echo $_GET['date_to'] ?? ''; ?>">
                    </div>

                    <button type="submit" class="filter-btn">Filter</button>
                </form>
            </div>

            <!-- عرض الفواتير -->
            <div class="checks-container">
                <?php
                // بناء استعلام SQL
                $query = "
                    SELECT 
                        u.UserName,
                        u.RoomNumber,
                        o.OrderID,
                        o.DateOrder,
                        o.TotalPrice,
                        GROUP_CONCAT(
                            CONCAT(p.ProductName, ' (', oi.Quantity, ') - $', 
                            FORMAT(p.Price * oi.Quantity, 2))
                            SEPARATOR '\n'
                        ) as OrderDetails
                    FROM `order` o
                    JOIN users u ON o.UserID = u.UserID
                    JOIN order_items oi ON o.OrderID = oi.OrderID
                    JOIN products p ON oi.ProductID = p.ProductID
                ";

                $conditions = [];
                $params = [];

                if (!empty($_GET['user'])) {
                    $conditions[] = "u.UserID = :user_id";
                    $params[':user_id'] = $_GET['user'];
                }

                if (!empty($_GET['date_from'])) {
                    $conditions[] = "DATE(o.DateOrder) >= :date_from";
                    $params[':date_from'] = $_GET['date_from'];
                }

                if (!empty($_GET['date_to'])) {
                    $conditions[] = "DATE(o.DateOrder) <= :date_to";
                    $params[':date_to'] = $_GET['date_to'];
                }

                if (!empty($conditions)) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }

                $query .= " GROUP BY o.OrderID ORDER BY o.DateOrder DESC";

                $stmt = $connection->prepare($query);
                $stmt->execute($params);
                $checks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($checks as $check): ?>
                    <div class="check-card">
                        <div class="check-header">
                            <div class="user-info">
                                <h3><?php echo htmlspecialchars($check['UserName']); ?></h3>
                                <p>Room: <?php echo htmlspecialchars($check['RoomNumber']); ?></p>
                            </div>
                            <div class="order-info">
                                <p>Order #<?php echo $check['OrderID']; ?></p>
                                <p class="date"><?php echo date('Y-m-d H:i', strtotime($check['DateOrder'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="check-details">
                            <pre><?php echo htmlspecialchars($check['OrderDetails']); ?></pre>
                        </div>
                        
                        <div class="check-total">
                            <h4>Total: $<?php echo number_format($check['TotalPrice'], 2); ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html> 