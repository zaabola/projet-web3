<?php
require_once 'C:/xampp/htdocs/reservation/Controller/GestionBus.php';

$gestionBus = new GestionBus();
$error = $success = "";

// Handle form submission using the PRG pattern
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add-bus'])) {
    $matricule = $_POST['matricule'];
    $nomChauffeur = $_POST['nom_chauffeur'];
    $depart = $_POST['depart'];
    $nbrPlace = $_POST['nbr_place'];
    $destination = $_POST['destination'];

    // Validate inputs
    $validationErrors = [];
    if (empty($matricule)) $validationErrors[] = "Le champ 'Matricule' est obligatoire.";
    if (empty($nomChauffeur)) $validationErrors[] = "Le champ 'Nom Chauffeur' est obligatoire.";
    if (empty($depart)) $validationErrors[] = "Le champ 'Départ' est obligatoire.";
    if (empty($nbrPlace)) $validationErrors[] = "Le champ 'Nombre de places' est obligatoire.";
    if (empty($destination)) $validationErrors[] = "Le champ 'Destination' est obligatoire.";

    if (empty($validationErrors)) {
        try {
            $gestionBus->addBus($matricule, $nomChauffeur, $depart, $nbrPlace, $destination);

            // Redirect after successful addition to prevent form resubmission
            header("Location: ajoutbus.php?success=1");
            exit();
        } catch (Exception $e) {
            $error = "Erreur lors de l'ajout du bus : " . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $validationErrors);
    }
}

// Check if success flag is set in the query string
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Bus ajouté avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ajouter un Bus</title>
    <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />

    <!-- Custom Styles -->
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
</head>
<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
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
                    <a class="nav-link active bg-gradient-dark text-white" href="../pages/ajoutbus.php">
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
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header">
                            <h6>Ajouter un Bus</h6>
                        </div>
                        <div class="card-body">
                            <!-- Success and Error Messages -->
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <!-- Add Bus Form -->
                            <form method="POST">
                                <div class="form-group">
                                    <label>Matricule</label>
                                    <input type="text" name="matricule" class="form-control" placeholder="Ex: 1234" value="<?= htmlspecialchars($_POST['matricule'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Nom Chauffeur</label>
                                    <input type="text" name="nom_chauffeur" class="form-control" placeholder="Ex: John Doe" value="<?= htmlspecialchars($_POST['nom_chauffeur'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Départ</label>
                                    <input type="time" name="depart" class="form-control" value="<?= htmlspecialchars($_POST['depart'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Nombre de Places</label>
                                    <input type="number" name="nbr_place" class="form-control" placeholder="Ex: 50" value="<?= htmlspecialchars($_POST['nbr_place'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Destination</label>
                                    <select name="destination" class="form-control">
                                        <option value="" disabled <?= empty($_POST['destination']) ? 'selected' : '' ?>>Choisir une destination</option>
                                        <option value="Tozeur" <?= ($_POST['destination'] ?? '') === "Tozeur" ? 'selected' : '' ?>>Tozeur</option>
                                        <option value="Djerba" <?= ($_POST['destination'] ?? '') === "Djerba" ? 'selected' : '' ?>>Djerba</option>
                                        <option value="El Jem" <?= ($_POST['destination'] ?? '') === "El Jem" ? 'selected' : '' ?>>El Jem</option>
                                        <option value="Sidi Bou Said" <?= ($_POST['destination'] ?? '') === "Sidi Bou Said" ? 'selected' : '' ?>>Sidi Bou Said</option>
                                        <option value="Carthage" <?= ($_POST['destination'] ?? '') === "Carthage" ? 'selected' : '' ?>>Carthage</option>
                                        <option value="Tunis" <?= ($_POST['destination'] ?? '') === "Tunis" ? 'selected' : '' ?>>Tunis</option>
                                        <option value="Dougga" <?= ($_POST['destination'] ?? '') === "Dougga" ? 'selected' : '' ?>>Dougga</option>
                                        <option value="Kairouan" <?= ($_POST['destination'] ?? '') === "Kairouan" ? 'selected' : '' ?>>Kairouan</option>
                                        <option value="Ain drahem et Tbarka" <?= ($_POST['destination'] ?? '') === "Ain drahem et Tbarka" ? 'selected' : '' ?>>Ain drahem et Tbarka</option>
                                    </select>
                                </div>


                                <button type="submit" name="add-bus" class="btn btn-success mt-3">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
