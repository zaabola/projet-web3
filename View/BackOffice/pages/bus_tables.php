<?php
require_once 'C:/xampp/htdocs/reservation/Controller/GestionBus.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$gestionBus = new GestionBus();
$success = $error = "";

// Handle actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete-bus'])) {
        $matricule = $_POST['matricule'] ?? '';  // Get the matricule from the POST data
        try {
            $gestionBus->deleteBus($matricule);  // Pass matricule to the deleteBus method
            $success = "Bus supprimé avec succès.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch buses
try {
    $buses = $gestionBus->getAllBuses();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des Bus</title>
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
                <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">Emprunt</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/dashboard.php">
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/reservation_tables.php">
                        <span class="nav-link-text ms-1">Reservation</span>
                    </a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/edit_reservation.php">
                        <span class="nav-link-text ms-1">Modification des reservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/ajoutbus.php">
                        <span class="nav-link-text ms-1">Ajouter un bus</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-gradient-dark text-white" href="../pages/bus_tables.php">
                        <span class="nav-link-text ms-1">Bus</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/edit_bus.php">
                        <span class="nav-link-text ms-1">Modification des bus</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header">
                            <h6>Gestion des Bus</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Matricule</th>
                                            <th>Nom Chauffeur</th>
                                            <th>Départ</th>
                                            <th>Nombre de Places</th>
                                            <th>Destination</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($buses as $bus): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bus['matricule'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bus['nom_chauffeur'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bus['depart'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bus['nbr_place'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bus['destination'] ?? '') ?></td>
                                                <td>
                                                    <!-- Modifier Button -->
                                                    <form method="GET" action="edit_bus.php" style="display:inline-block;">
                                                        <input type="hidden" name="matricule" value="<?= htmlspecialchars($bus['matricule'] ?? '') ?>">
                                                        <button type="submit" class="btn btn-sm btn-primary">Modifier</button>
                                                    </form>
                                                    
                                                    <!-- Supprimer Button -->
                                                    <form method="POST" style="display:inline-block;">
                                                        <input type="hidden" name="matricule" value="<?= htmlspecialchars($bus['matricule'] ?? '') ?>">
                                                        <button type="submit" name="delete-bus" class="btn btn-sm btn-danger">Supprimer</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
