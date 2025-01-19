<?php

require('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $dsn = "$dbtype:host=$host;port=$port;dbname=$dbname";

    try {
        // الاتصال بقاعدة البيانات
        $pdo = new PDO($dsn, $userName, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // استلام البيانات من الفورم
        $productName = $_POST['product_name'];
        $productPrice = $_POST['price'];
        $productDescription = $_POST['product_description'];

        // التعامل مع رفع الصورة
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $uploadDir = 'uploads/'; // مسار تخزين الصور
            $fileName = time() . "_" . basename($_FILES['product_image']['name']); // اسم فريد للصورة
            $targetFilePath = $uploadDir . $fileName;

            // التأكد من إنشاء المجلد إذا لم يكن موجودًا
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // نقل الصورة إلى المسار المحدد
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
                // إدخال البيانات في قاعدة البيانات
                $sql = "INSERT INTO products (ProductName, Price, productDescription, ProductImage) 
                        VALUES (:product_name, :price, :description, :product_image)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':product_name' => $productName,
                    ':price' => $productPrice,
                    ':description' => $productDescription,
                    ':product_image' => $fileName
                ]);

                header("Location: products.php");
                exit;
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
