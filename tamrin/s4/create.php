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
    $sql = "INSERT INTO products (name, slug, price, previous_price, short_description, description,
            quantity, is_active,deleted_at)
            VALUES (:name, :slug, :price, :previous_price, :short_description,:description, :quantity,:is_active,:deleted_at)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':slug', $_POST['slug']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->bindParam(':previous_price', $_POST['previous_price']);
    $stmt->bindParam(':short_description', $_POST['short_description']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':quantity', $_POST['quantity']);
    $stmt->bindParam(':is_active', $_POST['is_active']);
    $stmt->bindParam(':deleted_at', $_POST['deleted_at']);

    try {
        $stmt->execute();
        echo "Product inserted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
</head>

<body>
    <h1>Add Product</h1>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Slug:</label>
        <input type="text" name="slug" required><br>

        <label>Price:</label>
        <input type="number" name="price" required><br>

        <label>Previous Price:</label>
        <input type="number" name="previous_price" required><br>

        <label>Short Description:</label>
        <textarea name="short_description" required></textarea><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br>

        <label>Is Active:</label>
        <input type="number" name="is_active" required><br>

        <button type="submit">Create</button>
    </form>
</body>

</html>