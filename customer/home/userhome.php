<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// جلب معلومات المستخدم من قاعدة البيانات
require('../../db.php');
$stmt = $connection->prepare("SELECT UserName, RoomNumber, ProfileImage FROM users WHERE UserID = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// تخزين معلومات المستخدم في الجلسة إذا لم تكن موجودة
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = $user['UserName'];
    $_SESSION['room_number'] = $user['RoomNumber'];
    $_SESSION['profile_image'] = $user['ProfileImage'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@200..1000&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>User Dashboard</title>
</head>

<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house"></i><a href="./userhome.php">Home</a></li>
                <li><i class="fa-solid fa-receipt"></i><a href="./myorders.php">My Orders</a></li>
                <li><i class="fa-solid fa-right-from-bracket"></i><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="vr1"></div>
    </aside>
    <div class="vr2"></div>
    <div class="user-info">
                <h2>Welcome, <?php echo ($_SESSION['username']); ?></h2>
                <p>Room: <?php echo ($_SESSION['room_number']); ?></p>
            </div>
    <hr>

    <main>
    
        

            <div class="products">
                <?php
                $query = "SELECT * FROM products";
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['ProductID']; ?>">
                        <img src="../../allproduct/uploads/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>">
                        <h2><?php echo $product['ProductName']; ?></h2>
                        <h3 class="product-price">$<?php echo $product['Price']; ?></h3>
                        <div class="product-description"><?php echo $product['productDescription']; ?></div>
                        <div class="product-actions">
                            <div class="add-one" data-action="add"><i class="fa-solid fa-plus"></i></div>
                            <div class="product-quantity">1</div>
                            <div class="remove-one" data-action="remove"><i class="fa-solid fa-minus"></i></div>
                        </div>
                        <a href="#" class="add-to-cart">Add to Cart</a>
                    </div>
                <?php endforeach; ?>
            </div>
    </main>
   

    <div class="login-info">
    <div class="login-picture">
            <img src="../../alluser/uploads/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="User Profile Picture" width="50" height="50" style="border-radius: 50%; object-fit: cover;">
        </div>
        
        <h2> <?php echo ($_SESSION['username']); ?></h2> 
       </div>

    <div class="cart">
        <h2>My Cart</h2>
        <div class="cart-items" id="cart-items"></div>
        <div class="cart-total">
            <h3>Total</h3>
            <h3 class="product-price" id="cart-total-price">$0</h3>
        </div>
        <a href="#" class="checkout">Place Order</a>
    </div>
    <template id="cart-item-template">
        <div class="cart-item" data-cart-item-id="">
            <img src="" alt="">
            <h2 data-cart-item-name=""></h2>
            <h3 class="product-price" data-cart-item-price=""></h3>
            <div class="cart-item-actions">
                <div class="add-one" data-action="add" data-cart-item-action="add"><i class="fa-solid fa-plus"></i></div>
                <div class="product-quantity" data-cart-item-quantity="">1</div>
                <div class="remove-one" data-action="remove" data-cart-item-action="remove"><i class="fa-solid fa-minus"></i></div>
            </div>
        </div>
    </template>
    <script>
        // تخزين معرف المستخدم من PHP إلى JavaScript
        const userId = <?php echo $_SESSION['user_id']; ?>;
    </script>
    <script src="script.js"></script>
</body>

</html>