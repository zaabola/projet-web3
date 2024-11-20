<?php
// Configuration de la base de données
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

// Initialisation des messages
$success = $error = "";
$selectedReservation = null;
$validationErrors = [];

// Traitement des actions (suppression et modification)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete-reservation'])) {
        $id_reservation = intval($_POST['reservation-id'] ?? 0);
        if ($id_reservation > 0) {
            try {
                $sql = "DELETE FROM reservation WHERE id_reservation = :id_reservation";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id_reservation' => $id_reservation]);
                $success = "Réservation supprimée avec succès.";
            } catch (PDOException $e) {
                $error = "Erreur lors de la suppression : " . $e->getMessage();
            }
        } else {
            $error = "ID de réservation invalide.";
        }
    } elseif (isset($_POST['edit-reservation'])) {
        $id_reservation = intval($_POST['reservation-id'] ?? 0);
        if ($id_reservation > 0) {
            try {
                $sql = "SELECT * FROM reservation WHERE id_reservation = :id_reservation";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id_reservation' => $id_reservation]);
                $selectedReservation = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $error = "Erreur lors de la récupération de la réservation : " . $e->getMessage();
            }
        } else {
            $error = "ID de réservation invalide.";
        }
    } elseif (isset($_POST['update-reservation'])) {
        $id_reservation = intval($_POST['id_reservation']);
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $tel = $_POST['tel'];
        $destination = $_POST['destination'];
        $commentaire = $_POST['commentaire'];
        $date = date('Y-m-d H:i:s'); // Set the current date and time automatically

        // Validating inputs
        if (empty($nom)) {
            $validationErrors[] = "Le champ 'Nom' est obligatoire.";
        }
        if (empty($prenom)) {
            $validationErrors[] = "Le champ 'Prénom' est obligatoire.";
        }
        if (empty($mail)) {
            $validationErrors[] = "Le champ 'Email' est obligatoire.";
        }
        if (empty($tel)) {
            $validationErrors[] = "Le champ 'Téléphone' est obligatoire.";
        }
        if (empty($destination)) {
            $validationErrors[] = "Le champ 'Destination' est obligatoire.";
        }
        if (empty($commentaire)) {
            $validationErrors[] = "Le champ 'Commentaire' est obligatoire.";
        }

        // If there are no validation errors, proceed with the update
        if (empty($validationErrors)) {
            try {
                $sql = "UPDATE reservation SET 
                            nom = :nom, 
                            prenom = :prenom, 
                            mail = :mail, 
                            tel = :tel, 
                            destination = :destination, 
                            commentaire = :commentaire, 
                            date = :date 
                        WHERE id_reservation = :id_reservation";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':mail' => $mail,
                    ':tel' => $tel,
                    ':destination' => $destination,
                    ':commentaire' => $commentaire,
                    ':date' => $date,
                    ':id_reservation' => $id_reservation,
                ]);
                $success = "Réservation mise à jour avec succès.";
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
    }
}

// Lecture des réservations
$reservations = [];
try {
    $sql = "SELECT * FROM reservation ORDER BY date DESC";
    $stmt = $pdo->query($sql);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des réservations : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des Réservations</title>
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
    <script>
        // JavaScript pour la validation des formulaires
        function validateForm() {
            let form = document.forms["updateForm"];
            let errorMessages = [];

            // Vérification que tous les champs sont remplis
            if (form["nom"].value.trim() == "") {
                errorMessages.push("Le champ 'Nom' est obligatoire.");
            }
            if (form["prenom"].value.trim() == "") {
                errorMessages.push("Le champ 'Prénom' est obligatoire.");
            }
            if (form["mail"].value.trim() == "") {
                errorMessages.push("Le champ 'Email' est obligatoire.");
            }
            if (form["tel"].value.trim() == "") {
                errorMessages.push("Le champ 'Téléphone' est obligatoire.");
            }
            if (form["destination"].value.trim() == "") {
                errorMessages.push("Le champ 'Destination' est obligatoire.");
            }
            if (form["commentaire"].value.trim() == "") {
                errorMessages.push("Le champ 'Commentaire' est obligatoire.");
            }

            // Afficher les erreurs si présentes
            if (errorMessages.length > 0) {
                let errorDiv = document.getElementById("errorMessages");
                errorDiv.innerHTML = ""; // Clear previous errors
                errorMessages.forEach(function(msg) {
                    let errorItem = document.createElement("p");
                    errorItem.classList.add("alert", "alert-danger");
                    errorItem.textContent = msg;
                    errorDiv.appendChild(errorItem);
                });
                return false; // Prevent form submission
            }

            return true; // Proceed with form submission
        }
    </script>
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
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/tables.html">
          <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Reservation</span>
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
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Gestion des Réservations</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Destination</th>
                                            <th>Commentaire</th>
                                            <th>Date</th>
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
                                                <td>
                                                    <form method="POST" style="display:inline-block;">
                                                        <input type="hidden" name="reservation-id" value="<?= $reservation['id_reservation'] ?>">
                                                        <button type="submit" name="edit-reservation" class="btn btn-sm btn-primary">Modifier</button>
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

                            <?php if ($selectedReservation): ?>
                                <h3 class="mt-4">Modifier la Réservation</h3>
                                <div id="errorMessages"></div> <!-- Error messages area -->
                                <form method="POST" name="updateForm" onsubmit="return validateForm()">
                                    <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($selectedReservation['id_reservation']) ?>">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($selectedReservation['nom']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($selectedReservation['prenom']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="mail" class="form-control" value="<?= htmlspecialchars($selectedReservation['mail']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($selectedReservation['tel']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($selectedReservation['destination']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Commentaire</label>
                                        <textarea name="commentaire" class="form-control"><?= htmlspecialchars($selectedReservation['commentaire']) ?></textarea>
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
