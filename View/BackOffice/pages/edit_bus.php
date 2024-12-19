<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id']) || $_SESSION['type']=='user') {
    // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
    header("Location: ../../FrontOffice/logout.php");
    exit();
}
require_once 'C:/xampp/htdocs/reservation/Controller/GestionBus.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$gestionBus = new GestionBus();
$success = $error = "";
$selectedBus = null; // Initialize the $selectedBus variable

// Ensure the matricule is provided in the URL
if (isset($_GET['matricule']) && !empty($_GET['matricule'])) {
    $matricule = $_GET['matricule'];

    try {
        // Fetch the bus by matricule
        $selectedBus = $gestionBus->getBusByMatricule($matricule);

        // If no bus is found, set error
        if (!$selectedBus) {
            $error = "Bus non trouvé avec ce matricule.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    $error = "Matricule non fourni dans l'URL.";
}

// Handle form submission to update the bus
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-bus'])) {
    $matricule = $_POST['matricule'];  // Matricule is passed with the form
    $nomChauffeur = $_POST['nom_chauffeur'];
    $depart = $_POST['depart'];
    $nbrPlace = $_POST['nbr_place'];
    $destination = $_POST['destination'];

    // Validate and update the bus information
    try {
        $gestionBus->updateBus($matricule, $nomChauffeur, $depart, $nbrPlace, $destination);
        $success = "Bus mis à jour avec succès.";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modification des Bus</title>
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
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

<body class="g-sidenav-show bg-gray-100">
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
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/edit_bus.php">
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

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header">
                            <h6>Modification du Bus</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <?php if ($selectedBus): ?>
                                <form method="POST">
                                    <input type="hidden" name="matricule" value="<?= htmlspecialchars($selectedBus['matricule'] ?? '') ?>">

                                    <div class="form-group">
                                        <label for="nom_chauffeur">Nom Chauffeur</label>
                                        <input type="text" name="nom_chauffeur" class="form-control" value="<?= htmlspecialchars($selectedBus['nom_chauffeur'] ?? '') ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="depart">Départ</label>
                                        <input type="text" name="depart" class="form-control" value="<?= htmlspecialchars($selectedBus['depart'] ?? '') ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="nbr_place">Nombre de Places</label>
                                        <input type="number" name="nbr_place" class="form-control" value="<?= htmlspecialchars($selectedBus['nbr_place'] ?? '') ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="destination">Destination</label>
                                        <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($selectedBus['destination'] ?? '') ?>" required>
                                    </div>

                                    <button type="submit" name="update-bus" class="btn btn-primary mt-3">Mettre à jour</button>
                                </form>
                            <?php else: ?>
                                <p>Bus non trouvé.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
