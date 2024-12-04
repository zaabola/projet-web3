<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
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
        <span class="ms-1 text-sm text-dark">Emprunt</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/tables.html">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Complaints</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/produit.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Products</span>
          </a>
        </li>
        
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <?php
require_once 'c:/xampp/htdocs/projet/view/Backoffice/commande.php'; // Include the Commande class
?>
   <?php
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Retrieve form data
        $nom_produit = $_POST['Nom_Produit'] ?? null;
        $quantite_commande = $_POST['Qte'] ?? null;
        $adresse_client = $_POST['Adresse_client'] ?? null;
        $tel_client = $_POST['Tel_client'] ?? null;
        $nom_client = $_POST['Nom_client'] ?? null;
        $prenom_client = $_POST['Prenom_client'] ?? null;

        // Validate inputs
        if (!$nom_produit || !$quantite_commande || $quantite_commande <= 0 || !$adresse_client || !$tel_client || !$nom_client || !$prenom_client) {
            die("<p style='color: red;'>Invalid input data. Ensure all fields are filled correctly.</p>");
        }

        try {
            // Begin a transaction
            $pdo->beginTransaction();

            // Check product availability
            $check_sql = "SELECT id_produit, Qte FROM produit WHERE Nom_Produit = :nom_produit";
            $stmt = $pdo->prepare($check_sql);
            $stmt->execute(['nom_produit' => $nom_produit]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$produit) {
                throw new Exception("Product not found.");
            }

            if ($produit['Qte'] < $quantite_commande) {
                throw new Exception("Insufficient stock.");
            }

            // Insert into the commande table
            $commande_sql = "INSERT INTO commande (Adresse_client, Tel_client, Nom_client, Prenom_client, Nom_Produit) 
                            VALUES (:adresse_client, :tel_client, :nom_client, :prenom_client, :nom_produit)";
            $stmt = $pdo->prepare($commande_sql);
            $stmt->execute([
                'adresse_client' => $adresse_client,
                'tel_client' => $tel_client,
                'nom_client' => $nom_client,
                'prenom_client' => $prenom_client,
                'nom_produit' => $nom_produit
            ]);
            $id_commande = $pdo->lastInsertId(); // Get the newly created commande ID

            // Update product quantity
            $update_sql = "UPDATE produit SET Qte = Qte - :quantite WHERE id_produit = :id_produit";
            $stmt = $pdo->prepare($update_sql);
            $stmt->execute([
                'quantite' => $quantite_commande,
                'id_produit' => $produit['id_produit']
            ]);

            // Insert into ligne_commande table
            $ligne_commande_sql = "INSERT INTO ligne_commande (id_commande, id_produit, quantite) 
                                VALUES (:id_commande, :id_produit, :quantite)";
            $stmt = $pdo->prepare($ligne_commande_sql);
            $stmt->execute([
                'id_commande' => $id_commande,
                'id_produit' => $produit['id_produit'],
                'quantite' => $quantite_commande
            ]);

            // Commit the transaction
            $pdo->commit();

            // Redirect to avoid form re-submission (PRG Pattern)
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&commande_id=" . $id_commande);
            exit; // Ensure no further code is executed after the redirect
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }

    // Success message if redirected
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p style='color: green;'>Commande placed successfully! Commande ID: " . htmlspecialchars($_GET['commande_id']) . "</p>";
    }
   
    ?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
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
    <h1>Order Management System</h1>

    <!-- Form to create a new order -->
    <form method="POST" action="">
        <label for="Nom_Produit">Product Name:</label><br>
        <input type="text" id="Nom_Produit" name="Nom_Produit" placeholder="Enter Product Name" required><br><br>

        <label for="Qte">Quantity:</label><br>
        <input type="number" id="Qte" name="Qte" placeholder="Enter Quantity" required><br><br>

        <label for="Adresse_client">Client Address:</label><br>
        <input type="text" id="Adresse_client" name="Adresse_client" placeholder="Enter Client Address" required><br><br>

        <label for="Tel_client">Client Phone:</label><br>
        <input type="text" id="Tel_client" name="Tel_client" placeholder="Enter Client Phone" required><br><br>

        <label for="Nom_client">Client Last Name:</label><br>
        <input type="text" id="Nom_client" name="Nom_client" placeholder="Enter Client Last Name" required><br><br>

        <label for="Prenom_client">Client First Name:</label><br>
        <input type="text" id="Prenom_client" name="Prenom_client" placeholder="Enter Client First Name" required><br><br>

        <button type="submit" class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Place Commande</button>
    </form>
     <form id="updateOrderForm">
        <h2>Update an Order</h2>
        <input type="number" name="Id_commande" placeholder="Order ID" required>
        <input type="text" name="Adresse_client" placeholder="Client Address" required>
        <input type="text" name="Tel_client" placeholder="Client Phone Number" required>
        <input type="text" name="Nom_client" placeholder="Client First Name" required>
        <input type="text" name="Prenom_client" placeholder="Client Last Name" required>
        <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" type="submit">Update Order</button>
    </form>

    <!-- Form to delete an order -->
    <form id="deleteOrderForm">
        <h2>Delete an Order</h2>
        <input type="number" name="Id_commande" placeholder="Order ID" required>
        <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" type="submit">Delete Order</button>
    </form>

    <!-- Button to fetch all orders -->
    <button id="fetchOrdersButton" class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Fetch All Orders</button>
    <div id="ordersList"></div>

    <script>
        // Handle form submissions
        document.getElementById('createOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            fetch('tables.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error creating order:', error));
        });

        document.getElementById('updateOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'update');
            fetch('tables.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error updating order:', error));
        });

        document.getElementById('deleteOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'delete');
            fetch('tables.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error deleting order:', error));
        });

        // Fetch all orders
        document.getElementById('fetchOrdersButton').addEventListener('click', fetchOrders);

        function fetchOrders() {
            fetch('tables.php')
                .then(response => response.json())
                .then(data => {
                    const ordersList = document.getElementById('ordersList');
                    ordersList.innerHTML = ''; // Clear previous results
                    data.forEach(order => {
                        const orderDiv = document.createElement('div');
                        orderDiv.innerHTML = `
                            <p><strong>Order ID:</strong> ${order.Id_commande}</p>
                            <p><strong>Client Address:</strong> ${order.Adresse_client}</p>
                            <p><strong>Client Phone:</strong> ${order.Tel_client}</p>
                            <p><strong>Client Name:</strong> ${order.Nom_client} ${order.Prenom_client}</p>
                        `;
                        ordersList.appendChild(orderDiv);
                    });
                })
                .catch(error => console.error('Error fetching orders:', error));
        }
    </script>

    <!-- Form to update an existing order -->
   
  
</body>
</html>

</main>
</body>

</html>


<?php
// Database Connection
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Add Commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_commande'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $sql = "INSERT INTO commandes (name, quantity, price) VALUES (:name, :quantity, :price)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name, 'quantity' => $quantity, 'price' => $price]);
    echo "Commande added successfully!";
}

// Fetch Commande
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_commandes'])) {
    $sql = "SELECT * FROM commandes";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

// Update Commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_commande'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $sql = "UPDATE commandes SET name = :name, quantity = :quantity, price = :price WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id, 'name' => $name, 'quantity' => $quantity, 'price' => $price]);
    echo "Commande updated successfully!";
}

// Delete Commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_commande'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM commandes WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    echo "Commande deleted successfully!";
}
?>
