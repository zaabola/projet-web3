<?php
require_once 'C:/xampp/htdocs/reservation/Controller/GestionReservation.php';

$gestionReservation = new GestionReservation();
$error = $success = "";
$selectedReservation = null;

// Check if an ID is passed
if (isset($_GET['id'])) {
    $id_reservation = intval($_GET['id']);
    try {
        $selectedReservation = $gestionReservation->getReservationById($id_reservation);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else {
    $error = "Aucun ID de réservation fourni.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-reservation'])) {
    $id_reservation = intval($_POST['id_reservation']);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $tel = $_POST['tel'];
    $destination = $_POST['destination'];
    $commentaire = $_POST['commentaire'];
    $date = new DateTime();
    $matricule = $_POST['matricule'];

    // Validate inputs
    $validationErrors = [];
    if (empty($nom)) $validationErrors[] = "Le champ 'Nom' est obligatoire.";
    if (empty($prenom)) $validationErrors[] = "Le champ 'Prénom' est obligatoire.";
    if (empty($mail)) $validationErrors[] = "Le champ 'Email' est obligatoire.";
    if (empty($tel)) $validationErrors[] = "Le champ 'Téléphone' est obligatoire.";
    if (empty($destination)) $validationErrors[] = "Le champ 'Destination' est obligatoire.";

    if (empty($validationErrors)) {
        try {
            $reservation = new reservation($nom, $prenom, $mail, $tel, $destination, $commentaire, $date, null);
            $reservation->setId($id_reservation);
            $reservation->setMatricule($matricule);
            $gestionReservation->updateReservation($reservation);
            $success = "Réservation mise à jour avec succès.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = implode('<br>', $validationErrors); // Combine all validation errors into a single message
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modifier une Réservation</title>
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Input field styles */
        .form-control {
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
            transition: all 0.3s ease-in-out;
            width: 250px;
        }

        .form-control:focus {
            border-color: #5cb85c;
            box-shadow: 0 0 5px #5cb85c;
            outline: none;
        }

        /* Dropdown list styling */
        select.form-control {
            background-color: #f8f9fa;
            padding: 10px;
            font-size: 14px;
            color: #495057;
            appearance: none;
            border-radius: 5px;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='gray' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.5 6.5a.5.5 0 0 1 .707-.707l6 6a.5.5 0 0 1-.707.707l-6-6zm6 0a.5.5 0 0 1 .707-.707l6 6a.5.5 0 0 1-.707.707l-6-6z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
        }

        select.form-control:focus {
            border-color: #5cb85c;
            box-shadow: 0 0 5px #5cb85c;
            outline: none;
        }

        /* Button styling */
        .btn-success {
            background-color: #5cb85c;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #4cae4c;
        }

        /* Error message styling */
        .error-message {
            color: red;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        /* Alert box styling */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
    <script src="../assets/js/ModifcationFormControl.js" defer></script>
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
            <a class="nav-link text-dark" href="../pages/reservation_tables.php">
                <span class="nav-link-text ms-1">Reservation</span>
            </a>
            </li> 
            <li class="nav-item">
            <a class="nav-link active bg-gradient-dark text-white" href="../pages/edit_reservation.php">
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
                            <h6>Modifier la Réservation</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <?php if ($selectedReservation): ?>
                                <form method="POST" onsubmit="return verifyInputs();" novalidate>
                                    <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($selectedReservation['id_reservation']) ?>">
                                    <input type="hidden" name="matricule" value="<?= htmlspecialchars($selectedReservation['matricule']) ?>">

                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" id="last-name" class="form-control" value="<?= htmlspecialchars($selectedReservation['nom']) ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" name="prenom" id="first-name" class="form-control" value="<?= htmlspecialchars($selectedReservation['prenom']) ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="mail" id="mail" class="form-control" value="<?= htmlspecialchars($selectedReservation['mail']) ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" name="tel" id="tel" class="form-control" value="<?= htmlspecialchars($selectedReservation['tel']) ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <select name="destination" id="destination" class="form-control">
                                            <option value="" disabled <?= empty($selectedReservation['destination']) ? 'selected' : '' ?>>Choisir une excursion</option>
                                            <option value="Tozeur" <?= $selectedReservation['destination'] === "Tozeur" ? 'selected' : '' ?>>Tozeur</option>
                                            <option value="Djerba" <?= $selectedReservation['destination'] === "Djerba" ? 'selected' : '' ?>>Djerba</option>
                                            <option value="El Jem" <?= $selectedReservation['destination'] === "El Jem" ? 'selected' : '' ?>>El Jem</option>
                                            <option value="Sidi Bou Said" <?= $selectedReservation['destination'] === "Sidi Bou Said" ? 'selected' : '' ?>>Sidi Bou Said</option>
                                            <option value="Carthage" <?= $selectedReservation['destination'] === "Carthage" ? 'selected' : '' ?>>Carthage</option>
                                            <option value="Tunis" <?= $selectedReservation['destination'] === "Tunis" ? 'selected' : '' ?>>Tunis</option>
                                            <option value="Dougga" <?= $selectedReservation['destination'] === "Dougga" ? 'selected' : '' ?>>Dougga</option>
                                            <option value="Kairouan" <?= $selectedReservation['destination'] === "Kairouan" ? 'selected' : '' ?>>Kairouan</option>
                                            <option value="Ain drahem et Tbarka" <?= $selectedReservation['destination'] === "Ain drahem et Tbarka" ? 'selected' : '' ?>>Ain drahem et Tbarka</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Commentaire</label>
                                        <textarea name="commentaire" id="commentaire" class="form-control"><?= htmlspecialchars($selectedReservation['commentaire']) ?></textarea>
                                    </div>
                                    
                                    <button type="submit" name="update-reservation" class="btn btn-success mt-2">Mettre à jour</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

