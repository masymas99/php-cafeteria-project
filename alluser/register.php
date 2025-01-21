<?php
require('../db.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استلام البيانات من الفورم
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $roomNumber = trim($_POST['room_number']);

    // التحقق من البيانات
    if (strlen($name) < 3) {
        $errors['name'] = 'Name must be at least 3 characters';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    if (!is_numeric($roomNumber)) {
        $errors['room_number'] = 'Please enter a valid room number';
    }

    // إذا لم تكن هناك أخطاء، قم بمعالجة الصورة وحفظ البيانات
    if (empty($errors)) {
        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
            $uploadDir = 'uploads/';
            $fileName = time() . "_" . basename($_FILES['user_image']['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['user_image']['tmp_name'], $targetFilePath)) {
                try {
                    $sql = "INSERT INTO users (UserName, Email, Password, RoomNumber, ProfileImage) 
                            VALUES (:name, :email, :password, :room_number, :user_image)";
                    
                    $stmt = $connection->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':password' => $password,
                        ':room_number' => $roomNumber,
                        ':user_image' => $fileName
                    ]);

                    header("Location: users.php");
                    exit();
                } catch (PDOException $e) {
                    $errors['general'] = 'Database error: ' . $e->getMessage();
                }
            } else {
                $errors['user_image'] = 'Failed to upload file';
            }
        } else {
            $errors['user_image'] = 'Please select an image file';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .error-message {
            color: #9B4B72;
            font-size: 14px;
            margin-top: 0px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column; 
            align-items: center;
            margin-bottom: 5px;
            width: 500px;
        }

        .form-group.error input {
            border-color: #9B4B72;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 70%;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 81vh;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }
        input {
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #9B4B72;
        }

        button {
            background-color: #9B4B72;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #7c3c5a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group <?php echo isset($errors['name']) ? 'error' : ''; ?>">
                <input type="text" id="name" name="name" 
                    placeholder="Enter your name" 
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                    required>
                <?php if (isset($errors['name'])): ?>
                    <div class="error-message"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['email']) ? 'error' : ''; ?>">
                <input type="email" id="email" name="email" 
                    placeholder="Enter your email" 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                    required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error-message"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['password']) ? 'error' : ''; ?>">
                <input type="password" id="password" name="password" 
                    placeholder="Enter your password" 
                    required>
                <?php if (isset($errors['password'])): ?>
                    <div class="error-message"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>">
                <input type="password" id="confirm_password" name="confirm_password" 
                    placeholder="Confirm your password" 
                    required>
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="error-message"><?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['room_number']) ? 'error' : ''; ?>">
                <input type="text" id="room_number" name="room_number" 
                    placeholder="Enter room number" 
                    value="<?php echo isset($_POST['room_number']) ? htmlspecialchars($_POST['room_number']) : ''; ?>" 
                    required>
                <?php if (isset($errors['room_number'])): ?>
                    <div class="error-message"><?php echo $errors['room_number']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo isset($errors['user_image']) ? 'error' : ''; ?>">
                <input type="file" id="user_image" name="user_image" 
                    accept="image/*" 
                    required>
                <?php if (isset($errors['user_image'])): ?>
                    <div class="error-message"><?php echo $errors['user_image']; ?></div>
                <?php endif; ?>
            </div>

            <?php if (isset($errors['general'])): ?>
                <div class="error-message"><?php echo $errors['general']; ?></div>
            <?php endif; ?>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
