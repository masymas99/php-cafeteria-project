<?php
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['product_description']);
    $image = $_FILES['product_image'];

    try {
        // التحقق من القيم المدخلة
        if (empty($productName) || empty($price) || empty($description)) {
            throw new Exception("All fields are required.");
        }

        if (!is_numeric($price) || $price <= 0) {
            throw new Exception("Price must be a positive number.");
        }

        // استعلام التحديث الأساسي
        $query = "UPDATE products SET ProductName = :productName, Price = :price, productDescription = :description";

        // إذا كان هناك صورة مرفوعة
        if (!empty($image['name'])) {
            // التحقق من نوع الملف
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                throw new Exception("Invalid image type. Only JPG, PNG, and GIF are allowed.");
            }

            // تحديد مسار الصورة
            $imagePath = './uploads/' . uniqid() . '-' . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                throw new Exception("Failed to upload image.");
            }

            // تحديث حقل الصورة في الاستعلام
            $query .= ", ProductImage = :image";
        }

        $query .= " WHERE ProductID = :productId";

        // تحضير وتنفيذ الاستعلام
        $statement = $connection->prepare($query);
        $statement->bindParam(':productName', $productName, PDO::PARAM_STR);
        $statement->bindParam(':price', $price, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':productId', $productId, PDO::PARAM_INT);

        if (!empty($image['name'])) {
            $statement->bindParam(':image', $imagePath, PDO::PARAM_STR);
        }

        $statement->execute();

        // إعادة التوجيه إلى صفحة المنتجات
        header('Location: Products.php');
        exit;
    } catch (Exception $e) {
        // عرض الخطأ
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>