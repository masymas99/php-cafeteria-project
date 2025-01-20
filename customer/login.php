<?php
session_start();
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare("SELECT UserID, UserName, RoomNumber FROM users WHERE Email = ? AND Password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['username'] = $user['UserName'];
        $_SESSION['room_number'] = $user['RoomNumber'];
        header("Location: home/userhome.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!-- باقي كود صفحة تسجيل الدخول --> 