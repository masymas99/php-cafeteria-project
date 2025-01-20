<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['Email']), FILTER_SANITIZE_EMAIL);
    $newPassword = htmlspecialchars(trim($_POST['NewPassword']));
    $confirmPassword = htmlspecialchars(trim($_POST['ConfirmPassword']));

    // is empty
    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        echo "All fields are required.";
        exit;
    }
//matchy matchy
    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }
//email in sql
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email not found.";
        exit;
    }
//update pass
$stmt = $conn->prepare("UPDATE Users SET Password = ? WHERE Email = ?");
$stmt->bind_param("ss", $newPassword, $email);


    if ($stmt->execute()) {
        $_SESSION['user'] = $email;
        header("Location: login.php");
        exit;
    } else {
        echo "Error updating password.";
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
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

    .container {
      background-color: #fff;
      width: 350px;
      padding: 30px 20px;
      border-radius: 20px;
      border: 1px solid #000;
      box-shadow: 0px 0px 10px 5px rgba(188, 10, 200, 0.3);
      text-align: center;
      box-sizing: border-box;
    }

    h1 {
      font-size: 20px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
      letter-spacing: 1px;
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

    input:focus {
      outline: none;
      border-color:rgb(0, 0, 0);
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
  </style>
</head>
<body>
<div class="container">
    <h1>Forget Password</h1>
    <form action="forget-password.php" method="post">
      <input type="email" name="Email" placeholder="Enter your Email" required>
      <input type="password" name="NewPassword" placeholder="New Password" required>
      <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>
      <button type="submit">Reset Password</button>
    </form>
  </div>
</body>
</html>
