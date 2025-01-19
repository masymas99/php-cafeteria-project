<?php
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // بيانات الاتصال بقاعدة البيانات
    // $dbtype = "mysql";
    // $host = "localhost";
    // $dbname = "storedb";
    // $userName = "root";
    // $password = "";
    // $port = "8005";

    $dsn = "$dbtype:host=$host;port=$port;dbname=$dbname";

    try {
        // الاتصال بقاعدة البيانات
        $pdo = new PDO($dsn, $userName, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // استلام البيانات من الفورم
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $roomNumber = $_POST['room_number'];

        // التأكد من تطابق كلمتي المرور
        if ($password !== $confirmPassword) {
            die("كلمات المرور غير متطابقة.");
        }

        // التعامل مع رفع الصورة
        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
            $uploadDir = 'uploads/'; // مسار تخزين الصور
            $fileName = time() . "_" . basename($_FILES['user_image']['name']); // اسم فريد للصورة
            $targetFilePath = $uploadDir . $fileName;

            // التأكد من إنشاء المجلد إذا لم يكن موجودًا
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // نقل الصورة إلى المسار المحدد
            if (move_uploaded_file($_FILES['user_image']['tmp_name'], $targetFilePath)) {
                // إدخال البيانات في قاعدة البيانات
                $sql = "INSERT INTO users (UserName, Email, Password, RoomNumber, ProfileImage) 
                        VALUES (:name, :email, :password, :room_number, :user_image)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password,
                    ':room_number' => $roomNumber,
                    ':user_image' => $fileName
                ]);

                header("Location: users.php");
            } else {
                echo "فشل في رفع الملف.";
            }
        } else {
            echo "خطأ أثناء رفع الملف.";
        }
    } catch (PDOException $e) {
        echo "خطأ: " . $e->getMessage();
    }
}
?>
