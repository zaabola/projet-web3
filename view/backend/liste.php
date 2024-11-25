<?php
include('C:/xampp/htdocs/web/controller/donation_controller.php');

$donationController = new DonationController();
$success = $error = "";

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add-donation'])) {
        $name = htmlspecialchars($_POST['booking-form-name'] ?? '');
        $email = htmlspecialchars($_POST['booking-form-email'] ?? '');
        $amount = htmlspecialchars($_POST['booking-form-number'] ?? '');
        $message = htmlspecialchars($_POST['booking-form-message'] ?? '');

        if (!empty($name) && !empty($email) && is_numeric($amount) && $amount > 0) {
            $donationController->addDonation($name, $email, $amount, $message);
            $success = "Donation ajoutée avec succès !";
        } else {
            $error = "Veuillez remplir correctement tous les champs.";
        }
    }

    if (isset($_POST['delete-donation'])) {
        $id = intval($_POST['id_donation']);
        $donationController->deleteDonation($id);
        $success = "Donation supprimée avec succès.";
    }

    if (isset($_POST['edit-donation'])) {
        $id = intval($_POST['id_donation']);
        $donationToEdit = $donationController->getDonationById($id);
    }

    if (isset($_POST['update-donation'])) {
        $id = intval($_POST['id_donation']);
        $name = htmlspecialchars($_POST['booking-form-name']);
        $email = htmlspecialchars($_POST['booking-form-email']);
        $amount = htmlspecialchars($_POST['booking-form-number']);
        $message = htmlspecialchars($_POST['booking-form-message']);

        if (!empty($name) && !empty($email) && is_numeric($amount) && $amount > 0) {
            $donationController->updateDonation($id, $name, $email, $amount, $message);
            $success = "Donation mise à jour avec succès.";
        } else {
            $error = "Veuillez remplir correctement tous les champs.";
        }
    }
}

$donations = $donationController->listDonations();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Donation Management</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" rel="stylesheet" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <script src="../validation.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
                <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
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
                        <div class="mb-3">
                            <label for="booking-form-name">Name</label>
                            <input type="text" name="booking-form-name" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="booking-form-email">Email</label>
                            <input type="email" name="booking-form-email" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="booking-form-number">Amount</label>
                            <input type="number" name="booking-form-number" class="form-control" >
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
                                <input type="text" name="booking-form-name" class="form-control" value="<?= $donationToEdit['donor_name'] ?>" >
                            </div>
                            <div class="mb-3">
                                <label for="booking-form-email">Email</label>
                                <input type="email" name="booking-form-email" class="form-control" value="<?= $donationToEdit['donor_email'] ?>" >
                            </div>
                            <div class="mb-3">
                                <label for="booking-form-number">Amount</label>
                                <input type="number" name="booking-form-number" class="form-control" value="<?= $donationToEdit['donation_amount'] ?>" >
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
                                <th>ID</th>
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
                                    <td><?= $donation['id_donation'] ?></td>
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
