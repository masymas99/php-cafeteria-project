<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https:
    <link rel="preconnect" href="https:
    <link rel="preconnect" href="https:
    <!-- boot strab -->
    <!-- <link href="https:
    <link
        href="https:
        rel="stylesheet">
    <title>Admin Dashboard</title>
</head>

<body>
    <aside>
        <h1>C A F E T E R I A</h1>
        <nav>
            <ul>
                <li><i class="fa-solid fa-house" class="navitems"></i><a href="../adminhome/index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping" class="navitems"></i><a href="Products.php">Products</a></li>
                <li><i class="fa-solid fa-users" class="navitems"></i><a href="../alluser/users.php">Users</a></li>
                <li><i class="fa-solid fa-receipt" class="navitems"></i><a href="../allorders/orders.php">Orders</a></li>
                <li><i class="fa-solid fa-money-check" class="navitems"></i><a href="../allchecks/checks.php">Checks</a></li>
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


            <div class="Addproduct-modal modal" id="Addproductmodal">
                <div class="Addproduct-content">
                <button class="close-modal" onclick="closeCart()">×</button>

                    <h2>Add New Prouduct </h2>
                    <form action="addproduct.php" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="text" placeholder="product name" name="product_name"
                            aria-label="default input example">
                        <input class="form-control" type="text" placeholder="price" aria-label="default input example"
                            name="price">
                        <input class="form-control" type="text" placeholder="product description"
                            aria-label="default input example" name="product_description">
                        <input class="form-control" type="file" placeholder="product image"
                            aria-label="default input example" name="product_image">
                        <!-- <div class="Addproduct-actions"> -->

                        <button type="submit">Add product</button>
                        <!-- </div> -->
                    </form>

                </div>
            </div>



            <div class="product">
                <?php
                require('../db.php');
                $query = "SELECT * FROM products";
                $statment = $connection->prepare($query);
                $statment->execute();
                $products = $statment->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['ProductID']; ?>">
                        <img src="./uploads/<?php echo $product['ProductImage']; ?>"
                            alt="<?php echo $product['ProductName']; ?>">
                        <h2><?php echo $product['ProductName']; ?></h2>
                        <h3 class="product-price">$ <?php echo $product['Price']; ?></h3>
                        <div class="product-description"><?php echo $product['productDescription']; ?></div>
                        <div class="product-action">
                            <a href="#" onclick="openEditModal(
                                '<?php echo $product['ProductID']; ?>',
                                '<?php echo $product['ProductName']; ?>',
                                '<?php echo $product['Price']; ?>',
                                '<?php echo $product['productDescription']; ?>')">
                            <i class="fa-solid fa-edit"></i></a>
                            <a href="deleteproduct.php?product_id=<?php echo $product['ProductID']; ?>"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="Editproduct-modal modal" id="Editproductmodal">
            <div class="Editproduct-content">
            <button class="close-modal" onclick="closeCart()">×</button>

                <h2>Edit Product</h2>
                <form action="editproduct.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    <input class="form-control" type="text" placeholder="Product Name" name="product_name"
                        id="edit_product_name">
                    <input class="form-control" type="text" placeholder="Price" name="price" id="edit_product_price">
                    <input class="form-control" type="text" placeholder="Product Description" name="product_description"
                        id="edit_product_description">
                    <input class="form-control" type="file" placeholder="Product Image" name="product_image"
                        id="edit_product_image">
                    <button type="submit">Update Product</button>
                </form>
            </div>
        </div>
        <div class="Editproduct-modal" id="Editproductmodal">
            <div class="Editproduct-content">
                <button class="close-modal" onclick="closeEditModal()">×</button>
                <h2>Edit Product</h2>
                <form action="editproduct.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="edit_product_id">

                    <label for="edit_product_name">Product Name</label>
                    <input class="form-control" type="text" name="product_name" id="edit_product_name" required>

                    <label for="edit_product_price">Price</label>
                    <input class="form-control" type="number" name="price" id="edit_product_price" min="0" step="0.01"
                        required>

                    <label for="edit_product_description">Product Description</label>
                    <textarea class="form-control" name="product_description" id="edit_product_description" rows="4"
                        required></textarea>

                    <label for="edit_product_image">Product Image</label>
                    <input class="form-control" type="file" name="product_image" id="edit_product_image"
                        accept="image/*">

                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>

    </main>


    <div class="login-info">
    <?php
require('../db.php');

$query = "SELECT ProfileImage FROM users WHERE role='admin'";
$stmt = $connection->prepare($query);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="login-picture">
<img src="../alluser/uploads/<?php echo $admin['ProfileImage']; ?>" alt="Profile">
</div>  
      <h2>Admin</h2>
    </div>

    <script src="script.js"></script>
    <!-- <script src="https:
</body>

</html>