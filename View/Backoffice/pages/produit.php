<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="deletecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">DeleteOrder</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="updatecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">UpdateOrder</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="fetchcommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">fetchOrders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Complaints</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/produit.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Products</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/bib.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Gestion theme</span>
          </a>
        </li>
</ul>
<div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
      </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Update Product Quantity</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input, form button {
            margin: 5px 0;
            padding: 8px;
            width: 100%;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #ordersList div {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Update Product Quantity</h1>
    
    <?php
    // Database connection settings
    $host = "localhost";
    $dbname = "emprunt";
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

        <button type="submit" class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Update Quantity</button>
    </form>
</body>
</html>
