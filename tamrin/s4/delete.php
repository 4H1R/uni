<?php

$host = 'localhost';
$dbName = 'menu';
$user = 'root';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];

    // Prepare the SQL statement
    $sql = "UPDATE `products` SET `deleted_at`= NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':id', $id);

    // Execute the statement
    try {
        $stmt->execute();
        echo "Product deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete Product</title>
</head>

<body>
    <h1>Delete Product</h1>
    <form method="POST">
        <label>ID:</label>
        <input type="text" name="id" required><br>

        <button type="submit">Delete</button>
    </form>
</body>

</html>