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

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bindParam(1, $_GET['id']);
$stmt->execute();
$product = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Prepare the SQL statement
    $sql = "UPDATE products
            SET name = :name, slug = :slug, price = :price,previous_price = :previous_price,
            short_description = :short_description description = :description,quantity = :quantity,is_active = :isActive,
            WHERE id = :productId";
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
    $stmt->bindParam(':id', $_GET['id']);


    // Execute the statement
    try {
        $stmt->execute();
        echo "Product updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
</head>

<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

        <label>Slug:</label>
        <input type="text" name="slug" value="<?php echo htmlspecialchars($product['slug']); ?>" required><br>

        <label>Price:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

        <label>Previous Price:</label>
        <input type="number" name="previous_price" value="<?php echo htmlspecialchars($product['previous_price']); ?>" required><br>

        <label>Short Description:</label>
        <textarea name="short_description" required><?php echo htmlspecialchars($product['short_description']); ?></textarea><br>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br>

        <label>Is Active:</label>
        <input type="number" name="is_active" value="<?php echo htmlspecialchars($product['is_active']); ?>" required><br>

        <button type="submit">Update</button>
    </form>
</body>

</html>