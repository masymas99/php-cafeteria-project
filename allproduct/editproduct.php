<?php
require('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['product_description']);
    $image = $_FILES['product_image'];

    try {
        
        if (empty($productName) || empty($price) || empty($description)) {
            throw new Exception("All fields are required.");
        }

        if (!is_numeric($price) || $price <= 0) {
            throw new Exception("Price must be a positive number.");
        }

        
        $query = "UPDATE products SET ProductName = :productName, Price = :price, productDescription = :description";

        
        if (!empty($image['name'])) {
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                throw new Exception("Invalid image type. Only JPG, PNG, and GIF are allowed.");
            }

            
            $imagePath =  uniqid() . '-' . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                throw new Exception("Failed to upload image.");
            }

            
            $query .= ", ProductImage = :image";
        }

        $query .= " WHERE ProductID = :productId";

        
        $statement = $connection->prepare($query);
        $statement->bindParam(':productName', $productName, PDO::PARAM_STR);
        $statement->bindParam(':price', $price, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':productId', $productId, PDO::PARAM_INT);

        if (!empty($image['name'])) {
            $statement->bindParam(':image', $imagePath, PDO::PARAM_STR);
        }

        $statement->execute();

        
        header('Location: Products.php');
        exit;
    } catch (Exception $e) {
        
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>