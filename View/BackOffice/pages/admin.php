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
include('C:/xampp/htdocs/reservation/Controller/donation_management.php');

$donationManagementController = new DonationManagementController();
$success = $error = "";
$donationToEdit = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add-donation-management']) || isset($_POST['update-donation-management'])) {
        $idDonation = htmlspecialchars($_POST['id_donation'] ?? '');
        $adminName = htmlspecialchars($_POST['admin_name'] ?? '');
        $distributionDate = htmlspecialchars($_POST['distribution_date'] ?? '');
        $allocatedPercentage = floatval($_POST['allocated_percentage'] ?? 0);
        $managementId = intval($_POST['management_id'] ?? 0);

        try {
            if (isset($_POST['add-donation-management'])) {
                $donationManagementController->addDonationManagement($idDonation, $adminName, '', $distributionDate, 0, $allocatedPercentage);
                $success = "Donation management added successfully!";
            } elseif (isset($_POST['update-donation-management']) && $managementId) {
                $donationManagementController->updateDonationManagement($managementId, $idDonation, $adminName, '', $distributionDate, 0, $allocatedPercentage);
                $success = "Donation management updated successfully!";
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    if (isset($_POST['delete-donation-management'])) {
        $managementId = intval($_POST['management_id']);
        try {
            $donationManagementController->deleteDonationManagement($managementId);
            $success = "Donation management deleted successfully.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    if (isset($_POST['edit-donation-management'])) {
        $managementId = intval($_POST['management_id']);
        $donationToEdit = $donationManagementController->getDonationManagementById($managementId);

        if (!$donationToEdit) {
            $error = "No data found for this donation management.";
        }
    }
}

try {
    $donationsManagement = $donationManagementController->listDonationManagement();
    $seenManagementIds = [];
    $uniqueDonationsManagement = [];

    foreach ($donationsManagement as $management) {
        if (!in_array($management['management_id'], $seenManagementIds)) {
            $seenManagementIds[] = $management['management_id'];
            $donationDetails = $donationManagementController->getDonationDetailsById($management['id_donation']);
            $management['donor_name'] = $donationDetails['donor_name'] ?? 'Unknown';
            $management['donation_amount'] = $donationDetails['donation_amount'] ?? 0;

            $price_per_unit = 1;
            $totalPrice = $management['donation_amount'] * $price_per_unit;
            $allocatedPrice = $totalPrice * ($management['allocated_percentage'] / 100);
            $management['allocated_price'] = number_format($allocatedPrice, 2);

            $uniqueDonationsManagement[] = $management;
        }
    }
} catch (Exception $e) {
    $uniqueDonationsManagement = [];
    $error = $e->getMessage();
}
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
                        <a class="nav-link active bg-gradient-dark text-white" href="admin.php">
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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <!-- Main Content -->
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

        <div class="container-fluid-py-2">
            <div class="row">
                <div class="col-12">
                    <?php if ($error) : ?>
                        <div class="alert alert-danger" role="alert"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($success) : ?>
                        <div class="alert alert-success" role="alert"><?= $success ?></div>
                    <?php endif; ?>

                    <!-- Add Donation Form -->
                    <div class="col-md-12">
                        <h4><?= $donationToEdit ? "Edit Donation Management" : "Add Donation Management" ?></h4>
                        <form action="" method="POST">
                            <input type="hidden" name="management_id" value="<?= $donationToEdit ? $donationToEdit['management_id'] : '' ?>">
                            <div class="mb-3">
                                <label for="id_donation">Donation ID</label>
                                <input type="text" name="id_donation" value="<?= $donationToEdit['id_donation'] ?? '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="admin_name">Admin Name</label>
                                <input type="text" name="admin_name" value="<?= $donationToEdit['admin_name'] ?? '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="distribution_date">Distribution Date</label>
                                <input type="date" name="distribution_date" value="<?= $donationToEdit['distribution_date'] ?? '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="allocated_percentage">Allocated Percentage</label>
                                <input type="number" name="allocated_percentage" value="<?= $donationToEdit['allocated_percentage'] ?? '' ?>" class="form-control" step="0.01" min="0" max="100">
                            </div>
                            <button type="submit" class="btn btn-primary" name="<?= $donationToEdit ? 'update-donation-management' : 'add-donation-management' ?>">Save</button>
                        </form>
                    </div>
                </div>
            </div>

        
<div class="col-12">
    <h4 class="mt-4">Donation Management List</h4>
    <table class="table">
        <thead>
            <tr>
                <th>management_id</th>
                <th>Donation ID</th>
                <th>Donor Name</th>
                <th>Admin Name</th>
                <th>Distribution Date</th>
                <th>Allocated Percentage</th>
                <th>Allocated Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($uniqueDonationsManagement as $management): ?>
                <tr>
                    <td><?= $management['management_id'] ?></td>
                    <td><?= $management['id_donation'] ?></td>
                    <td><?= $management['donor_name'] ?></td>
                    <td><?= $management['admin_name'] ?></td>
                    <td><?= $management['distribution_date'] ?></td>
                    <td><?= $management['allocated_percentage'] ?>%</td>
                    <td><?= $management['allocated_price'] ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="management_id" value="<?= $management['management_id'] ?>">
                            <button type="submit" name="edit-donation-management" class="btn btn-warning">Edit</button>
                        </form>
                        <form action="" method="POST" class="d-inline">
                            <input type="hidden" name="management_id" value="<?= $management['management_id'] ?>">
                            <button type="submit" name="delete-donation-management" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    </main>
</body>
</html>
