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
    <link
        href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@200..1000&family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
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
        <!-- نافذة تعديل المنتج -->
        <div class="Editproduct-modal" id="Editproductmodal">
            <div class="Editproduct-content">
                <button class="close-modal" onclick="closeEditModal()">×</button>
                <h2>Edit Product</h2>
                <form action="editproduct.php" method="post" enctype="multipart/form-data">
                    <!-- Hidden input for Product ID -->
                    <input type="hidden" name="product_id" id="edit_product_id">

                    <!-- Product Name -->
                    <label for="edit_product_name">Product Name</label>
                    <input class="form-control" type="text" name="product_name" id="edit_product_name" required>

                    <!-- Product Price -->
                    <label for="edit_product_price">Price</label>
                    <input class="form-control" type="number" name="price" id="edit_product_price" min="0" step="0.01"
                        required>

                    <!-- Product Description -->
                    <label for="edit_product_description">Product Description</label>
                    <textarea class="form-control" name="product_description" id="edit_product_description" rows="4"
                        required></textarea>

                    <!-- Product Image -->
                    <label for="edit_product_image">Product Image</label>
                    <input class="form-control" type="file" name="product_image" id="edit_product_image"
                        accept="image/*">

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
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