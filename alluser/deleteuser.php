<?php
require('../db.php');

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    try {
        $query = "DELETE FROM users WHERE UserID = :userId";
        $statement = $connection->prepare($query);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();

        header('Location: users.php');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "user ID not provided.";
}
?>