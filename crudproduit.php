<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Update Product Quantity</title>
</head>
<body>
    <h1>Update Product Quantity</h1>
    
    <?php
    // Database connection settings
    $host = "localhost";
    $dbname = "empreinte1";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Fetch all products for the dropdown
    $products = [];
    try {
        $stmt = $pdo->query("SELECT id_produit, Nom_Produit, Qte FROM produit");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error fetching products: " . $e->getMessage() . "</p>";
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_produit = $_POST['Nom_Produit'] ?? null;
        $new_quantity = $_POST['Qte'] ?? null;

        // Validate inputs
        if (!$nom_produit || $new_quantity === null || $new_quantity < 0) {
            echo "<p style='color: red;'>Invalid input. Please provide a valid product name and quantity.</p>";
        } else {
            try {
                // Update product quantity
                $update_sql = "UPDATE produit SET Qte = :new_quantity WHERE Nom_Produit = :nom_produit";
                $stmt = $pdo->prepare($update_sql);
                $stmt->execute([
                    'new_quantity' => $new_quantity,
                    'nom_produit' => $nom_produit
                ]);

                echo "<p style='color: green;'>Quantity updated successfully for product: " . htmlspecialchars($nom_produit) . "</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>Error updating product: " . $e->getMessage() . "</p>";
            }
        }
    }
    ?>

    <!-- Form to update product quantity -->
    <form method="POST" action="">
        <label for="Nom_Produit">Select Product:</label><br>
        <select id="Nom_Produit" name="Nom_Produit" required>
            <option value="" disabled selected>-- Select a Product --</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php echo htmlspecialchars($product['Nom_Produit']); ?>">
                    <?php echo htmlspecialchars($product['Nom_Produit']) . " (Current Quantity: " . $product['Qte'] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="Qte">New Quantity:</label><br>
        <input type="number" id="Qte" name="Qte" placeholder="Enter new quantity" required><br><br>

        <button type="submit">Update Quantity</button>
    </form>
</body>
</html>
