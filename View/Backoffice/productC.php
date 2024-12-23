<?php
require_once 'commande.php'; // Include the Commande class
// require 'c:/xampp/htdocs/projet/config.php'; 

$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

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
        <button type="submit">Create Order</button>
    </form>

    <!-- Form to update an existing order -->
    <form id="updateOrderForm">
        <h2>Update an Order</h2>
        <input type="number" name="Id_commande" placeholder="Order ID" required>
        <input type="text" name="Adresse_client" placeholder="Client Address" required>
        <input type="text" name="Tel_client" placeholder="Client Phone Number" required>
        <input type="text" name="Nom_client" placeholder="Client First Name" required>
        <input type="text" name="Prenom_client" placeholder="Client Last Name" required>
        <button type="submit">Update Order</button>
    </form>

    <!-- Form to delete an order -->
    <form id="deleteOrderForm">
        <h2>Delete an Order</h2>
        <input type="number" name="Id_commande" placeholder="Order ID" required>
        <button type="submit">Delete Order</button>
    </form>

    <!-- Button to fetch all orders -->
    <button id="fetchOrdersButton">Fetch All Orders</button>
    <div id="ordersList"></div>

    <script>
        // Handle form submissions
        document.getElementById('createOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            fetch('productC.php', {
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
            fetch('productC.php', {
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
            fetch('productC.php', {
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
            fetch('productC.php')
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
