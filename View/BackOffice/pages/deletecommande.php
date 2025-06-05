<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id']) || $_SESSION['type'] == 'user') {
  // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
  header("Location: ../../FrontOffice/logout.php");
  exit();
}
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

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['Id_commande'])) {
    $Id_commande = $_POST['Id_commande'];

    try {
      // Check if the command exists
      $sqlCheck = "SELECT COUNT(*) FROM commande WHERE Id_commande = :Id_commande";
      $stmtCheck = $db->prepare($sqlCheck);
      $stmtCheck->bindParam(':Id_commande', $Id_commande, PDO::PARAM_INT);
      $stmtCheck->execute();
      $commandExists = $stmtCheck->fetchColumn();

      if ($commandExists) {
        // Delete related rows from `ligne_commande` (if they exist)
        $sqlLigne = "DELETE FROM ligne_commande WHERE Id_Commande = :Id_commande";
        $stmtLigne = $db->prepare($sqlLigne);
        $stmtLigne->bindParam(':Id_commande', $Id_commande, PDO::PARAM_INT);
        $stmtLigne->execute();

        // Delete the command from the `commande` table
        $sqlCommande = "DELETE FROM commande WHERE Id_commande = :Id_commande";
        $stmtCommande = $db->prepare($sqlCommande);
        $stmtCommande->bindParam(':Id_commande', $Id_commande, PDO::PARAM_INT);

        if ($stmtCommande->execute()) {
          $message = "Command deleted successfully!";
        } else {
          $message = "Failed to delete command.";
        }
      } else {
        $message = "Invalid Command ID.";
      }
    } catch (PDOException $e) {
      $message = "Error deleting command: " . $e->getMessage();
    }
  } else {
    $message = "Command ID is required.";
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
          <a class="nav-link text-dark" href="ajoutuser.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Add user</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Sales</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ReservationDashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Reservation dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="table.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="deletecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Delete order</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="updatecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Update order</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="fetchcommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">fetch orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Order complaints</span>
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
            <span class="nav-link-text ms-1">Themes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reservation_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_reservation.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ajoutbus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Add bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/bus_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Buses</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_bus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="liste.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="admin.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donations manager</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="jointure.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donors</span>
          </a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="test.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit Donations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">volonteers</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">

    </div>
  </aside>
</body>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Command</title>
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

      form input,
      form button {
        margin: 5px 0;
        padding: 10px;
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

      .message {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
      }

      .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
      }

      .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
      }
    </style>
  </head>

  <body>
    <h1>Delete a Command</h1>

    <!-- Show a message if available -->
    <?php if (isset($message)): ?>
      <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <!-- Form to delete a command -->
    <form method="POST" action="" onsubmit="return validateDeleteForm()">
      <label for="Id_commande">Command ID:</label>
      <span id="commandIdError" style="color: red;"></span>
      <input type="number" id="Id_commande" name="Id_commande" placeholder="Enter Command ID"><br><br>
      <button type="submit" class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white">Delete Command</button>
    </form>

    <script>
      function validateDeleteForm() {
        var isValid = true;

        var commandId = document.getElementById('Id_commande').value;

        // Clear previous error messages
        document.getElementById('commandIdError').innerText = '';

        // Validate Command ID
        if (commandId === '') {
          document.getElementById('commandIdError').innerText = 'Command ID is required.';
          isValid = false;
        }

        return isValid;
      }
    </script>

  </body>

  </html>
