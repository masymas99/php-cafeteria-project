<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- boot strab -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@200..1000&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>

<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i  class="fa-solid fa-house" class="navitems"></i><a href="../adminhome/index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping"  class="navitems"></i><a href="Products.php"  >Products</a></li>
                <li><i class="fa-solid fa-users" class="navitems"></i><a href="../alluser/users.php">Users</a></li>
                <li><i class="fa-solid fa-receipt" class="navitems"></i><a href="#">Orders</a></li>
                <li><i class="fa-solid fa-money-check" class="navitems"></i><a href="#">Checks</a></li>
            </ul>
        </nav>

        <div class="vr1"></div>
    </aside>
    <!-- <div class="vr2"></div> -->

    <hr>

    <main>
        <div class="container">
            <div class="header">
                <h1> All product</h1>
                <a href="#" onclick="openCart()" class="btn-add-products">
                    <i class="fa-solid fa-plus"></i>
                    <h3>Add Product</h3>
                </a>
            </div>


            <div class="Addproduct-modal" id="Addproductmodal">
                <div class="Addproduct-content">
                    <h2>Add New Prouduct </h2>
                   <form action="addproduct.php" method="post" enctype="multipart/form-data">
                   <input class="form-control" type="text" placeholder="product name" name="product_name" aria-label="default input example">
                   <input class="form-control" type="text" placeholder="price" aria-label="default input example" name="price">
                   <input class="form-control" type="text" placeholder="product description" aria-label="default input example" name="product_description">
                   <input class="form-control" type="file" placeholder="product image" aria-label="default input example" name="product_image">
                   <!-- <div class="Addproduct-actions"> -->
                    
                   <button type="submit" >Add product</button>
                   <!-- </div> -->
                   </form>
                   
                </div>
            </div>



            <div class="product">
                <?php
                require('db.php');
                $query = "SELECT * FROM products";
                $statment = $connection->prepare($query);
                $statment->execute();
                $products = $statment->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['ProductID']; ?>">
                        <div class="vr3"></div>
                        <img src="./uploads/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>">
                        <h2><?php echo $product['ProductName']; ?></h2>
                        <h3 class="product-price">$ <?php echo $product['Price']; ?></h3>
                        <div class="product-description"><?php echo $product['productDescription']; ?></div>
                        <div class="product-actions">
                            <!-- <div class="add-one" data-action="add"><i class="fa-solid fa-plus"></i></div>
                            <div class="product-quantity" data-quantity="1">1</div>
                            <div class="remove-one" data-action="remove"><i class="fa-solid fa-minus"></i></div> -->
                        </div>
                        <div class="product-action">
                            <i class="fa-solid fa-edit"></i>
                            <i class="fa-solid fa-list"></i>
                            <i class="fa-solid fa-trash"></i>
                        </div>
                        <!-- <a href="#" class="add-to-cart" data-action="add-to-cart">Add to Cart</a> -->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>



    <div class="login-info">
        <div class="login-picture"></div>
        <h2>Admin</h2>
    </div>


    <script src="script.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
</body>

</html>