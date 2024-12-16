<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

try {
    // Establish the database connection
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle the update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_commande = $_POST['Id_commande'] ?? null;
    $Adresse_client = $_POST['Adresse_client'] ?? null;
    $Tel_client = $_POST['Tel_client'] ?? null;
    $Nom_client = $_POST['Nom_client'] ?? null;
    $Prenom_client = $_POST['Prenom_client'] ?? null;
    $Nom_Produit = $_POST['Nom_Produit'] ?? null;

    if ($Id_commande) {
        try {
            // Build the dynamic SQL query
            $fieldsToUpdate = [];
            $params = [':Id_commande' => $Id_commande];

            if ($Adresse_client) {
                $fieldsToUpdate[] = "Adresse_client = :Adresse_client";
                $params[':Adresse_client'] = $Adresse_client;
            }
            if ($Tel_client) {
                $fieldsToUpdate[] = "Tel_client = :Tel_client";
                $params[':Tel_client'] = $Tel_client;
            }
            if ($Nom_client) {
                $fieldsToUpdate[] = "Nom_client = :Nom_client";
                $params[':Nom_client'] = $Nom_client;
            }
            if ($Prenom_client) {
                $fieldsToUpdate[] = "Prenom_client = :Prenom_client";
                $params[':Prenom_client'] = $Prenom_client;
            }
            if ($Nom_Produit) {
                // Check if the product exists
                $product_check_sql = "SELECT Id_produit FROM produit WHERE Nom_Produit = :Nom_Produit";
                $stmt = $db->prepare($product_check_sql);
                $stmt->execute([':Nom_Produit' => $Nom_Produit]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($product) {
                    $product_id = $product['Id_produit'];
                    $fieldsToUpdate[] = "id_panier = :id_panier";
                    $params[':id_panier'] = $product_id;
                } else {
                    throw new Exception("Product not found.");
                }
            }

            if (!empty($fieldsToUpdate)) {
                // Create the SQL query
                $sql = "UPDATE commande SET " . implode(', ', $fieldsToUpdate) . " WHERE Id_commande = :Id_commande";
                $stmt = $db->prepare($sql);
                $stmt->execute($params);

                echo "Commande updated successfully!";
            } else {
                echo "No fields to update.";
            }
        } catch (PDOException $e) {
            echo "Error updating commande: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Id_commande is required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Material Dashboard 3 by Creative Tim</title>
  <!-- Fonts and icons -->
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
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
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
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/updatecommande.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
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
          <a class="nav-link text-dark" href="../pages/produit.php">
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
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card">
          <div class="card-header pb-0">
    <h6>Update Commande</h6>
</div>
<div class="card-body px-0 pt-0 pb-2">
    <div class="table-responsive p-0">
    <form method="POST" action="" onsubmit="return validateUpdateForm()">
    <label for="Id_commande">Commande ID (required):</label><br>
    <span id="commandIdError" style="color: red;"></span>
    <input type="number" id="Id_commande" name="Id_commande"><br><br>

    <label for="Adresse_client">Adresse Client:</label><br>
    <span id="clientAddressError" style="color: red;"></span>
    <input type="text" id="Adresse_client" name="Adresse_client" placeholder="Enter new Adresse"><br><br>

    <label for="Tel_client">Tel Client:</label><br>
    <span id="clientPhoneError" style="color: red;"></span>
    <input type="text" id="Tel_client" name="Tel_client" placeholder="Enter new Tel"><br><br>

    <label for="Nom_client">Nom Client:</label><br>
    <span id="clientLastNameError" style="color: red;"></span>
    <input type="text" id="Nom_client" name="Nom_client" placeholder="Enter new Nom"><br><br>

    <label for="Prenom_client">Prenom Client:</label><br>
    <span id="clientFirstNameError" style="color: red;"></span>
    <input type="text" id="Prenom_client" name="Prenom_client" placeholder="Enter new Prenom"><br><br>

    <label for="Nom_Produit">Nom Produit:</label><br>
    <span id="productNameError" style="color: red;"></span>
    <input type="text" id="Nom_Produit" name="Nom_Produit" placeholder="Enter new Produit Name"><br><br>

    <button type="submit" class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white">Update Commande</button>
</form>

<script>
    function validateUpdateForm() {
        var isValid = true;

        var commandId = document.getElementById('Id_commande').value;
        var clientAddress = document.getElementById('Adresse_client').value;
        var clientPhone = document.getElementById('Tel_client').value;
        var clientLastName = document.getElementById('Nom_client').value;
        var clientFirstName = document.getElementById('Prenom_client').value;
        var productName = document.getElementById('Nom_Produit').value;

        // Clear previous error messages
        document.getElementById('commandIdError').innerText = '';
        document.getElementById('clientAddressError').innerText = '';
        document.getElementById('clientPhoneError').innerText = '';
        document.getElementById('clientLastNameError').innerText = '';
        document.getElementById('clientFirstNameError').innerText = '';
        document.getElementById('productNameError').innerText = '';

        // Validate Command ID
        if (commandId === '') {
            document.getElementById('commandIdError').innerText = 'Commande ID is required.';
            isValid = false;
        }

        // Validate Adresse Client
        if (clientAddress === '') {
            document.getElementById('clientAddressError').innerText = 'Adresse Client is required.';
            isValid = false;
        }

        // Validate Tel Client
        if (clientPhone === '') {
            document.getElementById('clientPhoneError').innerText = 'Tel Client is required.';
            isValid = false;
        }

        // Validate Nom Client
        if (clientLastName === '') {
            document.getElementById('clientLastNameError').innerText = 'Nom Client is required.';
            isValid = false;
        }

        // Validate Prenom Client
        if (clientFirstName === '') {
            document.getElementById('clientFirstNameError').innerText = 'Prenom Client is required.';
            isValid = false;
        }

        // Validate Nom Produit
        if (productName === '') {
            document.getElementById('productNameError').innerText = 'Nom Produit is required.';
            isValid = false;
        }

        return isValid;
    }
</script>

    </div>
</div>
<script>
    function validateUpdateForm() {
        var isValid = true;

        var commandId = document.getElementById('Id_commande').value;
        var clientAddress = document.getElementById('Adresse_client').value;
        var clientPhone = document.getElementById('Tel_client').value;
        var clientLastName = document.getElementById('Nom_client').value;
        var clientFirstName = document.getElementById('Prenom_client').value;
        var productName = document.getElementById('Nom_Produit').value;

        // Clear previous error messages
        document.getElementById('commandIdError').innerText = '';
        document.getElementById('clientAddressError').innerText = '';
        document.getElementById('clientPhoneError').innerText = '';
        document.getElementById('clientLastNameError').innerText = '';
        document.getElementById('clientFirstNameError').innerText = '';
        document.getElementById('productNameError').innerText = '';

        // Validate Command ID
        if (commandId === '') {
            document.getElementById('commandIdError').innerText = 'Commande ID is required.';
            isValid = false;
        }

        return isValid;
    }
</script>
