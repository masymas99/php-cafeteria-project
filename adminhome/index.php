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
    <title>Admin Dashboard</title>
</head>

<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house" class="navitems"></i><a href="./index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping" class="navitems"></i><a href="../allproduct/products.php"  >Products</a></li>
                <li><i class="fa-solid fa-users" class="navitems"></i><a href="../alluser/users.php">Users</a></li>
                <li><i class="fa-solid fa-receipt" class="navitems"></i><a href="#">Orders</a></li>
                <li><i class="fa-solid fa-money-check" class="navitems"></i><a href="#">Checks</a></li>
            </ul>
        </nav>

        <div class="vr1"></div>
    </aside>
    <div class="vr2"></div>

    <hr>

    <main>
        <div class="container">
            <h1>Admin Menu</h1>
            <div class="product">
                <?php
                require('../db.php');
                $query = "SELECT * FROM products";
                $statment = $connection->prepare($query);
                $statment->execute();
                $products = $statment->fetchAll(PDO::FETCH_ASSOC);

                foreach ($products as $product): ?>

                    <div class="product-card" data-product-id="<?php echo $product['ProductID']; ?>">
                        <img src="../allproduct/uploads/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>">
                        <h2><?php echo $product['ProductName']; ?></h2>
                        <h3 class="product-price">$ <?php echo $product['Price']; ?></h3>
                        <div class="product-description"><?php echo $product['productDescription']; ?></div>
                        <div class="product-actions">
                            <div class="add-one" data-action="add"><i class="fa-solid fa-plus"></i></div>
                            <div class="product-quantity" data-quantity="1">1</div>
                            <div class="remove-one" data-action="remove"><i class="fa-solid fa-minus"></i></div>
                        </div>
                        <a href="#" class="add-to-cart" data-action="add-to-cart">Add to Cart</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <div class="login-info">
        <div class="login-picture"></div>
        <h2>Admin</h2>
    </div>

    <div class="cart">
        <div class="cart-for-user">
            <h3>Cart for</h3>
            <select class="form-select" aria-label="Default select example">
                <option selected>Open this select user</option>

                <?php require('../db.php');
                $query = "SELECT * FROM users";
                $statment = $connection->prepare($query);
                $statment->execute();
                $users = $statment->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user): ?>
                    <option value="<?php echo $user['UserID']; ?>"><?php echo $user['UserName']; ?></option>
                <?php endforeach;
                ?>
            </select>
        </div>
        <div class="cart-items" id="cart-items">
        </div>
        <div class="cart-total">
            <h2>Total</h2>
            <h3 class="product-price" id="cart-total-price">$ 0</h3>
        </div>
        <a href="#" class="checkout">Checkout</a>
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
    <script src="script.js"></script>
</body>

</html>