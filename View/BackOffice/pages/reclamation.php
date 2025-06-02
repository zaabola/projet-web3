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
// Error reporting to help with debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$dbname = "emprunt"; // Replace with your database name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Handle form submission for Create, Update, Delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Create operation
  if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $id_commande = $_POST['Id_commande'];
    $commentaire = $_POST['Commentaire'];
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $email = $_POST['Email'];
    $tel = $_POST['Tel'];

    // Start transaction
    $pdo->beginTransaction();

    try {
      // Insert reclamation
      $sql = "INSERT INTO reclamation VALUES (NULL, :id_commande, :commentaire, :nom, :prenom, :email, :tel)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        'id_commande' => $id_commande,
        'commentaire' => $commentaire,
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'tel' => $tel
      ]);

      // Delete corresponding commande
      $sql = "DELETE FROM commande WHERE Id_commande = :id_commande";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['id_commande' => $id_commande]);

      // Commit transaction
      $pdo->commit();

      $message = "Reclamation created successfully. Corresponding commande deleted successfully.";
    } catch (PDOException $e) {
      // Rollback transaction in case of error
      $pdo->rollBack();
      $message = "Failed to create reclamation and delete corresponding commande: " . $e->getMessage();
      error_log($e->getMessage());
    }
  }

  // Update operation
  if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_reclamation = $_POST['Id_reclamation'];
    $id_commande = $_POST['Id_commande'];
    $commentaire = $_POST['Commentaire'];
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $email = $_POST['Email'];
    $tel = $_POST['Tel'];

    $sql = "UPDATE reclamation SET Id_commande = :id_commande, Commentaire = :commentaire, Nom = :nom, 
                Prenom = :prenom, Email = :email, Tel = :tel WHERE Id_reclamation = :id_reclamation";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(compact('id_reclamation', 'id_commande', 'commentaire', 'nom', 'prenom', 'email', 'tel'))) {
      $message = "Reclamation updated successfully.";
    } else {
      $message = "Failed to update reclamation.";
    }
  }

  // Delete operation
  if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id_reclamation = $_POST['Id_reclamation'];

    $sql = "DELETE FROM reclamation WHERE Id_reclamation = :id_reclamation";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['id_reclamation' => $id_reclamation])) {
      $message = "Reclamation deleted successfully.";
    } else {
      $message = "Failed to delete reclamation.";
    }
  }
}

// Determine the total number of reclamations
try {
  $sql = "SELECT COUNT(*) FROM reclamation";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $total_reclamations = $stmt->fetchColumn();
} catch (PDOException $e) {
  die("Error fetching total reclamations: " . $e->getMessage());
}

// Set the number of reclamations per page and calculate the total number of pages
$reclamations_per_page = 5;
$total_pages = ceil($total_reclamations / $reclamations_per_page);

// Get the current page from the query parameter; default to 1 if not present
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the OFFSET for the SQL query
$offset = ($current_page - 1) * $reclamations_per_page;

// Fetch reclamations for the current page
try {
  $sql = "SELECT * FROM reclamation LIMIT :limit OFFSET :offset";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':limit', $reclamations_per_page, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();

  // Fetch all rows as an associative array
  $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching reclamations: " . $e->getMessage());
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
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ReservationDashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">ReservationDashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="table.php">
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
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/reclamation.php">
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
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reservation_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Reservation</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_reservation.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Modif des reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ajoutbus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Ajouter un bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/bus_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_bus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Modification des bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="liste.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Liste</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="admin.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Management</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="jointure.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Tableaux</span>
          </a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="test.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">credit</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">volontaires</span>
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
    <title>Reclamation CRUD</title>
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

      .pagination {
        display: flex;
        justify-content: center;
        padding: 20px 0;
      }

      .pagination a {
        margin: 0 5px;
        padding: 10px 15px;
        text-decoration: none;
        color: #000;
        /* Black text color */
        border: 1px solid #000;
        /* Black border color */
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
      }

      .pagination a:hover {
        background-color: #000;
        /* Black background color on hover */
        color: #fff;
        /* White text color on hover */
      }

      .pagination a.active {
        background-color: #000;
        /* Black background color for active page */
        color: #fff;
        /* White text color for active page */
        pointer-events: none;
      }
    </style>
  </head>

  <body>
    <h1>Reclamation CRUD</h1>

    <!-- Message output -->
    <?php if (isset($message)): ?>
      <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Form to create a new reclamation -->
    <form method="POST">
      <h2>Create a New Reclamation</h2>
      <input type="number" name="Id_commande" placeholder="Commande ID" required>
      <input type="text" name="Commentaire" placeholder="Commentaire" required>
      <input type="text" name="Nom" placeholder="Nom" required>
      <input type="text" name="Prenom" placeholder="Prenom" required>
      <input type="email" name="Email" placeholder="Email" required>
      <input type="text" name="Tel" placeholder="Tel" required>
      <input type="hidden" name="action" value="create">
      <button type="submit" class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white">Create Reclamation</button>
    </form>

    <!-- Form to update an existing reclamation -->
    <form method="POST">
      <h2>Update a Reclamation</h2>
      <input type="number" name="Id_reclamation" placeholder="Reclamation ID" required>
      <input type="number" name="Id_commande" placeholder="Commande ID" required>
      <input type="text" name="Commentaire" placeholder="Commentaire" required>
      <input type="text" name="Nom" placeholder="Nom" required>
      <input type="text" name="Prenom" placeholder="Prenom" required>
      <input type="email" name="Email" placeholder="Email" required>
      <input type="text" name="Tel" placeholder="Tel" required>
      <input type="hidden" name="action" value="update">
      <button type="submit" class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white">Update Reclamation</button>
    </form>

    <!-- Form to delete a reclamation -->
    <form method="POST">
      <h2>Delete a Reclamation</h2>
      <input type="number" name="Id_reclamation" placeholder="Reclamation ID" required>
      <input type="hidden" name="action" value="delete">
      <button type="submit" class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white">Delete Reclamation</button>
    </form>

    <!-- Display all reclamations -->
    <h2>All Reclamations</h2>
    <div id="reclamationsList">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Commande ID</th>
            <th>Commentaire</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Email</th>
            <th>Tel</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reclamations as $reclamation): ?>
            <tr>
              <td><?php echo $reclamation['Id_reclamation']; ?></td>
              <td><?php echo $reclamation['Id_commande']; ?></td>
              <td><?php echo $reclamation['Commentaire']; ?></td>
              <td><?php echo $reclamation['Nom']; ?></td>
              <td><?php echo $reclamation['Prenom']; ?></td>
              <td><?php echo $reclamation['Email']; ?></td>
              <td><?php echo $reclamation['Tel']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination controls -->
    <div class="pagination">
      <?php if ($current_page > 1): ?>
        <a href="?page=<?= $current_page - 1 ?>" class="pagination-btn">Previous</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <a href="?page=<?= $current_page + 1 ?>" class="pagination-btn">Next</a>
      <?php endif; ?>
    </div>
  </body>

  </html>
