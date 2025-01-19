<?php
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $userName = trim($_POST['editname']);
    $userEmail = trim($_POST['editemail']);
    $userPassword = trim($_POST['editpassword']);
    $confirmPassword = trim($_POST['editconfirm_password']);
    $roomNumber = trim($_POST['editroom_number']);
    $image = $_FILES['edituser_image'];

    try {
        // التحقق من الحقول المطلوبة
        if (empty($userName) || empty($userEmail) || empty($userPassword) || empty($confirmPassword) || empty($roomNumber)) {
            throw new Exception("All fields are required.");
        }
        // التحقق من تطابق كلمات المرور
        if ($userPassword !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }
        // التحقق من صحة البريد الإلكتروني
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // إنشاء استعلام التحديث
        $query = "UPDATE users SET userName = :userName, Email = :userEmail, Password = :userPassword, RoomNumber = :roomNumber";
        $params = [
            ':userName' => $userName,
            ':userEmail' => $userEmail,
            ':userPassword' => password_hash($userPassword, PASSWORD_BCRYPT),
            ':roomNumber' => $roomNumber
        ];

        // معالجة رفع الصورة
        if (!empty($image['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                throw new Exception("Invalid image type.");
            }

            $uploadDir = 'uploads/'; // تحديد مجلد التخزين
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // إنشاء المجلد إذا لم يكن موجودًا
            }

            $imagePath = './uploads/'. uniqid() . '-' . basename($image['name']); // تعيين المسار الكامل
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                throw new Exception("Failed to upload image.");
            }

            $query .= ", ProfileImage = :imagePath";
            $params[':imagePath'] = $imagePath;
        }

        // إضافة شرط التحديث
        $query .= " WHERE UserID = :userId";
        $params[':userId'] = $userId;

        // تنفيذ الاستعلام
        $statement = $connection->prepare($query);
        $statement->execute($params);

        // إعادة التوجيه
        header("Location: users.php");
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
