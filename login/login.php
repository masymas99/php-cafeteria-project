<?php
require 'db.php';  

session_start();


// any errors
ini_set('display_errors', 1);
error_reporting(E_ALL);


$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserEmail = trim($_POST['Email']);
    $UserPassword = trim($_POST['Password']);

    // empty email or pass
    if (empty($UserEmail) || empty($UserPassword)) {
        $error_message = "Email and Password cannot be empty.";
    }
 // email format
    elseif (!filter_var($UserEmail, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }

   // Search email in db
    else {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
        $stmt->bind_param("s", $UserEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            //confirm admin
            if ($row['role'] === "admin") {
                if ($UserPassword === $row['Password']) {
                    $_SESSION['user'] = $UserEmail;
                    $_SESSION['role'] = "admin";
                    session_regenerate_id(true); 
                    header("Location: ../adminhome/index.php");
                    exit;
                } else {
                    $error_message = "Incorrect password.";
                }
            }

         // match user pass
            elseif ($UserPassword === $row['Password']) {
                $_SESSION['user'] = $UserEmail;
                $_SESSION['role'] = "user";
                $_SESSION['user_id'] = $row['UserID'];
                session_regenerate_id(true); 
                header("Location: ../customer/home/userhome.php ?id=" . $row['UserID']);
                exit;
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Email is not registered.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fef7f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }
        form {
            width: 300px;
            height: 360px;
            background-color: #fff;
            padding: 20px;
            border-radius: 50px;
            display: inline-block;
            border: 1px solid #000;
            box-shadow: 0px 0px 10px 5px rgba(188, 10, 200, 0.3);
            margin: 0 auto;
        }
        form h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-align: center;
            text-transform: uppercase;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            background-color: #fafafa;
            box-sizing: border-box;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: rgb(2, 2, 2);
            box-shadow: 0 0 5px rgba(246, 160, 121, 0.5);
        }
        button {
            width: 100%;
            padding: 10px 0;
            background-color: #f6a079;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e57e4e;
        }
        .error-message {
            color: #ff0000; 
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
            <h2>CAFETERIA</h2>
            <input type="email" name="Email" placeholder="Email" value="<?php echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>" required>
            <input type="password" name="Password" placeholder="Password" required>
            <a href="forget-password.php">Forget Password ?</a>
            <button type="submit">Login</button>
            <!-- message error-->
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
