<?php
require_once 'C:/xampp/htdocs/reservation/Controller/GestionReservation.php';

$gestionReservation = new GestionReservation();
$success = $error = "";

// Handle actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete-reservation'])) {
        $id_reservation = intval($_POST['reservation-id'] ?? 0);
        try {
            $gestionReservation->deleteReservation($id_reservation);
            $success = "Réservation supprimée avec succès.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch reservations
try {
    $reservations = $gestionReservation->getAllReservations();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des Réservations</title>
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
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
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
            </li>
            <li class="nav-item">
            <a class="nav-link active bg-gradient-dark text-white" href="../pages/reservation_tables.php">
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
            <a class="nav-link text-dark" href="../pages/bus_tables.php">
                <span class="nav-link-text ms-1">Bus</span>
            </a>
            </li>
            <li class="nav-item">
            <a class="nav-link text-dark" href="../pages/edit_bus.php">
                <span class="nav-link-text ms-1">Modification des bus</span>
            </a>
            </li>
        </div>
        
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header">
                            <h6>Gestion des Réservations</h6>
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
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Destination</th>
                                            <th>Commentaire</th>
                                            <th>Date</th>
                                            <th>matricule</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservations as $reservation): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($reservation['nom']) ?></td>
                                                <td><?= htmlspecialchars($reservation['prenom']) ?></td>
                                                <td><?= htmlspecialchars($reservation['mail']) ?></td>
                                                <td><?= htmlspecialchars($reservation['tel']) ?></td>
                                                <td><?= htmlspecialchars($reservation['destination']) ?></td>
                                                <td><?= htmlspecialchars($reservation['commentaire']) ?></td>
                                                <td><?= htmlspecialchars($reservation['date']) ?></td>
                                                <td><?= htmlspecialchars($reservation['matricule']) ?></td>
                                                <td>
                                                    <form method="GET" action="edit_reservation.php" style="display:inline-block;">
                                                        <input type="hidden" name="id" value="<?= $reservation['id_reservation'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-primary">Modifier</button>
                                                    </form>
                                                    <form method="POST" style="display:inline-block;">
                                                        <input type="hidden" name="reservation-id" value="<?= $reservation['id_reservation'] ?>">
                                                        <button type="submit" name="delete-reservation" class="btn btn-sm btn-danger">Supprimer</button>
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
