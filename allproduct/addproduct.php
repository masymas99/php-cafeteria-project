<?php

require('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $dsn = "$dbtype:host=$host;port=$port;dbname=$dbname";

    try {
        
        $pdo = new PDO($dsn, $userName, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $productName = $_POST['product_name'];
        $productPrice = $_POST['price'];
        $productDescription = $_POST['product_description'];

        
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $uploadDir = 'uploads/'; 
            $fileName = time() . "_" . basename($_FILES['product_image']['name']); 
            $targetFilePath = $uploadDir . $fileName;

            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
                
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
