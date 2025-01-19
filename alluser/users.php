<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <li><i class="fa-solid fa-house" class="navitems"></i><a href="../adminhome/index.php">Home</a></li>
                <li><i class="fa-solid fa-basket-shopping" class="navitems"></i><a href="../allproduct/products.php">Products</a></li>
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
                <h1> All Users</h1>
                <a href="#" onclick="openCart()" class="btn-add-products">
                    <i class="fa-solid fa-plus"></i>
                    <h3>Add user</h3>
                </a>
            </div>


            <div class="Addproduct-modal modal" id="Addproductmodal">
                <div class="Addproduct-content">
                <button class="close-modal" onclick="closeCart()">×</button>
                    <h2>Add New user </h2>
                    <form action="register.php" method="POST" enctype="multipart/form-data">
                        <input type="text" name="name" required placeholder="User Name">
                        <input type="email" name="email" required placeholder="User email">
                        <input type="password" name="password" required placeholder="User password">
                        <input type="password" name="confirm_password" required placeholder="confirm password">
                        <input type="text" name="room_number" required placeholder="User room number">
                        <input type="file" name="user_image" required>
                        <button type="submit">Submit</button>
                    </form>



                </div>
            </div>



            <div class="product">

                <?php
                require('../db.php');
                $query = "SELECT * FROM users";
                $statment = $connection->prepare($query);
                $statment->execute();
                $users = $statment->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $user): ?>

                    <div class="product-card" data-product-id="<?php echo $user['UserID']; ?>">
                        <div class="vr3"></div>
                        <img src="./uploads/<?php echo $user['ProfileImage']; ?>"
                            alt="<?php echo htmlspecialchars($user['ProfileImage']); ?>"
                            style="width:150px; height:150px; border-radius:50%; object-fit:cover;">
                        <h2><?php echo $user['UserName']; ?></h2>
                        <div class="room-info">
                            <h4>
                            Room Number :   
                            </h4>
                            <h3 class="Roomnumber"> <?php echo $user['RoomNumber']; ?></h3>
                        </div>
                        
                        <div class="product-actions">
                        </div>
                        <div class="product-action">
                            <a href="#" class="edit-user">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="deleteuser.php?user_id=<?php echo $user['UserID']; ?>"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- نافذة التعديل -->
        <div class="Edituser-modal modal" id="EdituserModal">
            <div class="Edituser-content">
                <button class="close-modal" onclick="closeEditModal()">×</button>
                <h2>Edit User</h2>
                <form action="edituser.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <input type="text" name="editname" id="edit_user_name" required placeholder="User Name">
                    <input type="email" name="editemail" id="edit_user_email" required placeholder="User email">
                    <input type="password" name="editpassword" id="edit_user_password" required placeholder="User password">
                    <input type="password" name="editconfirm_password" id="edit_user_confirm_password" required placeholder="Confirm password">
                    <input type="text" name="editroom_number" id="edit_user_room_number" required placeholder="User room number">
                    <img id="edit_user_image_preview" alt="User Image Preview" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                    <input type="file" name="edituser_image" id="edit_user_image">
                    <button type="submit">Update User</button>
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