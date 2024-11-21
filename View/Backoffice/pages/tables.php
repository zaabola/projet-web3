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
        
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <?php
require_once 'c:/xampp/htdocs/projet/view/Backoffice/commande.php'; // Include the Commande class
// require 'c:/xampp/htdocs/projet/config.php'; 

$host = "localhost";
$username = "root";
$password = "";
$dbname = "empreinte";

// Database connection
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Create an instance of the Commande class
$commande = new Commande($db);

// Handle different actions based on the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new order
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $Adresse_client = $_POST['Adresse_client'];
        $Tel_client = $_POST['Tel_client'];
        $Nom_client = $_POST['Nom_client'];
        $Prenom_client = $_POST['Prenom_client'];

        if ($commande->createCommande($Adresse_client, $Tel_client, $Nom_client, $Prenom_client)) {
            echo "Order created successfully!";
        } else {
            echo "Failed to create order.";
        }
    }

    // Update an existing order
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $Id_commande = $_POST['Id_commande'];
        $Adresse_client = $_POST['Adresse_client'];
        $Tel_client = $_POST['Tel_client'];
        $Nom_client = $_POST['Nom_client'];
        $Prenom_client = $_POST['Prenom_client'];

        if ($commande->updateCommande($Id_commande, $Adresse_client, $Tel_client, $Nom_client, $Prenom_client)) {
            echo "Order updated successfully!";
        } else {
            echo "Failed to update order.";
        }
    }

    // Delete an order
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $Id_commande = $_POST['Id_commande'];

        if ($commande->deleteCommande($Id_commande)) {
            echo "Order deleted successfully!";
        } else {
            echo "Failed to delete order.";
        }
    }
}

// Handle reading orders
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all orders
    $orders = $commande->getAllCommandes();
    echo json_encode($orders); // Return orders as JSON
}

// Example of getting a specific order by ID
if (isset($_GET['Id_commande'])) {
    $Id_commande = $_GET['Id_commande'];
    $order = $commande->getCommandeById($Id_commande);
    echo json_encode($order); // Return the specific order as JSON
}

?>
<table id="ordersTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Client Address</th>
                <th>Client Phone</th>
                <th>Client Name</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dynamic PHP Content Here -->
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td><?= htmlspecialchars($order['Id_commande']); ?></td>
                    <td><?= htmlspecialchars($order['Adresse_client']); ?></td>
                    <td><?= htmlspecialchars($order['Tel_client']); ?></td>
                    <td><?= htmlspecialchars($order['Nom_client'] . " " . $order['Prenom_client']); ?></td>
                    
                </tr>
            <?php } ?>
        </tbody>
    </table>
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
    <form id="createOrderForm">
        <h2>Create a New Order</h2>
        <input type="text" name="Adresse_client" placeholder="Client Address" required>
        <input type="text" name="Tel_client" placeholder="Client Phone Number" required>
        <input type="text" name="Nom_client" placeholder="Client First Name" required>
        <input type="text" name="Prenom_client" placeholder="Client Last Name" required>
        <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" type="submit">Create Order</button>
    </form>

    <!-- Form to update an existing order -->
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
</body>
</html>

</main>
</body>

</html>