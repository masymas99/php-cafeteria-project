
<?php
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $userName = trim($_POST['editname']);
    $userEmail = trim($_POST['editemail']);
    $userPassword = trim($_POST['editpassword']);
    $confirmPassword = trim($_POST['editconfirm_password']);
    $roomNumber = trim($_POST['editroom_number']);
    $image = $_FILES['edituser_image'];

    try {
        if (empty($userName) || empty($userEmail) || empty($userPassword) || empty($confirmPassword) || empty($roomNumber)) {
            throw new Exception("All fields are required.");
        }
        if ($userPassword !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        $query = "UPDATE users SET userName = :userName, userEmail = :userEmail, userPassword = :userPassword, roomNumber = :roomNumber";
        $params = [
            ':userName' => $userName,
            ':userEmail' => $userEmail,
            ':userPassword' => password_hash($userPassword, PASSWORD_BCRYPT),
            ':roomNumber' => $roomNumber
        ];

        if (!empty($image['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                throw new Exception("Invalid image type.");
            }
            $imagePath = './uploads/' . uniqid() . '-' . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                throw new Exception("Failed to upload image.");
            }
            $query .= ", ProfileImage = :imagePath";
            $params[':imagePath'] = $imagePath;
        }

        $query .= " WHERE UserID = :userId";
        $params[':userId'] = $userId;

        $statement = $connection->prepare($query);
        $statement->execute($params);

        header("Location: users.php");
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
