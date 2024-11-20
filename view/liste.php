<?php
// Connexion à la base de données
$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$success = $error = "";

// Ajout d'une donation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-donation'])) {
    $name = htmlspecialchars($_POST['booking-form-name'] ?? '');
    $email = htmlspecialchars($_POST['booking-form-email'] ?? '');
    $amount = htmlspecialchars($_POST['booking-form-number'] ?? '');
    $message = htmlspecialchars($_POST['booking-form-message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($amount)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Veuillez entrer un email valide.";
        } elseif (!is_numeric($amount) || $amount <= 0) {
            $error = "Le montant doit être un nombre positif.";
        } else {
            try {
                // Vérification des doublons avant d'ajouter
                $checkQuery = "SELECT COUNT(*) FROM donation WHERE donor_email = :email AND donation_amount = :amount";
                $checkStmt = $pdo->prepare($checkQuery);
                $checkStmt->execute([':email' => $email, ':amount' => $amount]);
                $existingDonationCount = $checkStmt->fetchColumn();

                if ($existingDonationCount > 0) {
                    $error = "Une donation avec ce montant et cet email existe déjà.";
                } else {
                    // Si aucun doublon trouvé, on insère la donation
                    $sql = "INSERT INTO donation (donor_name, donor_email, donation_amount, message, donation_date) 
                            VALUES (:name, :email, :amount, :message, NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':name' => $name, ':email' => $email, ':amount' => $amount, ':message' => $message]);
                    $success = "Donation ajoutée avec succès !";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Mise à jour d'une donation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-donation'])) {
    $id_donation = intval($_POST['id_donation']);
    $name = htmlspecialchars($_POST['booking-form-name']);
    $email = htmlspecialchars($_POST['booking-form-email']);
    $amount = htmlspecialchars($_POST['booking-form-number']);
    $message = htmlspecialchars($_POST['booking-form-message']);

    if (!empty($name) && !empty($email) && !empty($amount)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Veuillez entrer un email valide.";
        } elseif (!is_numeric($amount) || $amount <= 0) {
            $error = "Le montant doit être un nombre positif.";
        } else {
            try {
                // Mise à jour de la donation
                $sql = "UPDATE donation SET donor_name = :name, donor_email = :email, donation_amount = :amount, message = :message WHERE id_donation = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':amount' => $amount,
                    ':message' => $message,
                    ':id' => $id_donation
                ]);
                $success = "Donation mise à jour avec succès.";
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Suppression d'une donation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-donation'])) {
    $id_donation = intval($_POST['id_donation']);

    try {
        $sql = "DELETE FROM donation WHERE id_donation = :id_donation";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_donation' => $id_donation]);
        $success = "Donation supprimée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Récupération des donations
try {
    $donations = $pdo->query("SELECT * FROM donation ORDER BY donation_date DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des donations : " . $e->getMessage();
}

// Vérification pour afficher le formulaire d'édition
if (isset($_POST['edit-donation'])) {
    $id_donation = $_POST['edit-donation'];
    $stmt = $pdo->prepare("SELECT * FROM donation WHERE id_donation = :id");
    $stmt->execute([':id' => $id_donation]);
    $donationToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>Donation Management</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" rel="stylesheet" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
                <img src="assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">بصمة</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="dashboard.php">
                        <i class="material-symbols-rounded opacity-5">dashboard</i>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-gradient-dark text-white" href="list.php">
                        <i class="material-symbols-rounded opacity-5">table_view</i>
                        <span class="nav-link-text ms-1">Tables</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tables</li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="logout.php" class="btn btn-dark">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <?php if ($error) : ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($success) : ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h4>Add a Donation</h4>
                    <form action="" method="POST">
                        <div class="mb-3">1
                            <label for="booking-form-name">Name</label>
                            <input type="text" name="booking-form-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="booking-form-email">Email</label>
                            <input type="email" name="booking-form-email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="booking-form-number">Amount</label>
                            <input type="number" name="booking-form-number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="booking-form-message">Message</label>
                            <textarea name="booking-form-message" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" name="add-donation" class="btn btn-primary">Add Donation</button>
                    </form>
                </div>

                <?php if (isset($donationToEdit)) : ?>
                    <div class="col-md-6">
                        <h4>Edit Donation</h4>
                        <form action="" method="POST">
                            <input type="hidden" name="id_donation" value="<?= $donationToEdit['id_donation'] ?>">
                            <div class="mb-3">
                                <label for="booking-form-name">Name</label>
                                <input type="text" name="booking-form-name" class="form-control" value="<?= $donationToEdit['donor_name'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="booking-form-email">Email</label>
                                <input type="email" name="booking-form-email" class="form-control" value="<?= $donationToEdit['donor_email'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="booking-form-number">Amount</label>
                                <input type="number" name="booking-form-number" class="form-control" value="<?= $donationToEdit['donation_amount'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="booking-form-message">Message</label>
                                <textarea name="booking-form-message" class="form-control" rows="4"><?= $donationToEdit['message'] ?></textarea>
                            </div>
                            <button type="submit" name="update-donation" class="btn btn-primary">Update Donation</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <h4>Donation List</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donations as $donation) : ?>
                                <tr>
                                    <td><?= $donation['donor_name'] ?></td>
                                    <td><?= $donation['donor_email'] ?></td>
                                    <td><?= $donation['donation_amount'] ?></td>
                                    <td><?= $donation['message'] ?></td>
                                    <td>
                                        <form action="" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_donation" value="<?= $donation['id_donation'] ?>">
                                            <button type="submit" name="delete-donation" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        <form action="" method="POST" style="display:inline;">
                                            <input type="hidden" name="edit-donation" value="<?= $donation['id_donation'] ?>">
                                            <button type="submit" name="edit-donation-btn" class="btn btn-warning btn-sm">Edit</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
