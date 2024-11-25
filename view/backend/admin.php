<?php
include('C:/xampp/htdocs/web/controller/donation_management.php');

$donationManagementController = new DonationManagementController();
$success = $error = "";
$donationToEdit = null;

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add donation management
    if (isset($_POST['add-donation-management'])) {
        $idDonation = htmlspecialchars($_POST['id_donation']);
        $adminName = htmlspecialchars($_POST['admin-name']);
        $recipientName = htmlspecialchars($_POST['recipient-name']);
        $distributionDate = htmlspecialchars($_POST['distribution-date']);
        $quantity = htmlspecialchars($_POST['quantity']);
        $allocatedPercentage = htmlspecialchars($_POST['allocated-percentage']) ?? null;

        if (!empty($idDonation) && !empty($adminName) && !empty($recipientName) && is_numeric($quantity) && $quantity > 0) {
            try {
                $donationManagementController->addDonationManagement($idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage);
                $success = "Donation management added successfully!";
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $error = "Please fill all the required fields correctly.";
        }
    }

    // Delete donation management
    if (isset($_POST['delete-donation-management'])) {
        $managementId = intval($_POST['management_id']);
        try {
            $donationManagementController->deleteDonationManagement($managementId);
            $success = "Donation management deleted successfully.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    // Edit donation management (show the edit form)
    if (isset($_POST['edit-donation-management'])) {
        $managementId = intval($_POST['management_id']);
        $donationToEdit = $donationManagementController->getDonationManagementById($managementId);

        // Check if the returned data is valid before accessing it
        if (!$donationToEdit) {
            $error = "No data found for this donation management.";
        }
    }

    // Update donation management
    if (isset($_POST['update-donation-management'])) {
        $managementId = intval($_POST['management_id']);
        $idDonation = htmlspecialchars($_POST['id_donation']);
        $adminName = htmlspecialchars($_POST['admin-name']);
        $recipientName = htmlspecialchars($_POST['recipient-name']);
        $distributionDate = htmlspecialchars($_POST['distribution-date']);
        $quantity = htmlspecialchars($_POST['quantity']);
        $allocatedPercentage = htmlspecialchars($_POST['allocated-percentage']) ?? null;

        if (!empty($idDonation) && !empty($adminName) && !empty($recipientName) && is_numeric($quantity) && $quantity > 0) {
            try {
                $donationManagementController->updateDonationManagement($managementId, $idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage);
                $success = "Donation management updated successfully.";
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $error = "Please fill all the required fields correctly.";
        }
    }
}

// List all donation management entries
$donationsManagement = $donationManagementController->listDonationManagement();
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
    <!-- Sidebar -->
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

        <div class="container-fluid py-4">
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
                                <input type="text" name="id_donation" class="form-control" value="<?= $donationToEdit ? $donationToEdit['id_donation'] : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="admin-name">Admin Name</label>
                                <input type="text" name="admin-name" class="form-control" value="<?= $donationToEdit ? $donationToEdit['admin_name'] : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name">Recipient Name</label>
                                <input type="text" name="recipient-name" class="form-control" value="<?= $donationToEdit ? $donationToEdit['recipient_name'] : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="distribution-date">Distribution Date</label>
                                <input type="date" name="distribution-date" class="form-control" value="<?= $donationToEdit ? $donationToEdit['distribution_date'] : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="<?= $donationToEdit ? $donationToEdit['quantity'] : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="allocated-percentage">Allocated Percentage</label>
                                <input type="number" name="allocated-percentage" class="form-control" value="<?= $donationToEdit ? $donationToEdit['allocated_percentage'] : '' ?>" min="0" max="100" step="0.01">
                            </div>
                            <button type="submit" name="<?= $donationToEdit ? 'update-donation-management' : 'add-donation-management' ?>" class="btn btn-<?= $donationToEdit ? 'warning' : 'success' ?>"><?= $donationToEdit ? 'Update' : 'Add' ?></button>
                        </form>
                    </div>

                    <!-- Donation Management Table -->
                    <div class="col-md-12 mt-4">
                        <h4>Donation Management List</h4>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Management ID</th>
                                    <th>Donation ID</th>
                                    <th>Admin Name</th>
                                    <th>Recipient Name</th>
                                    <th>Quantity</th>
                                    <th>Allocated Percentage</th>
                                    <th>Price After Allocation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donationsManagement as $management) : ?>
                                    <?php
                                    $price_per_unit = 10; // Example price per unit
                                    $totalPrice = $management['quantity'] * $price_per_unit;
                                    $allocatedPrice = $totalPrice * ($management['allocated_percentage'] / 100);
                                    ?>
                                    <tr>
                                        <td><?= $management['management_id'] ?></td>
                                        <td><?= $management['id_donation'] ?></td>
                                        <td><?= $management['admin_name'] ?></td>
                                        <td><?= $management['recipient_name'] ?></td>
                                        <td><?= $management['quantity'] ?></td>
                                        <td><?= $management['allocated_percentage'] ?>%</td>
                                        <td><?= number_format($allocatedPrice, 2) ?> USD</td>
                                        <td>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="management_id" value="<?= $management['management_id'] ?>">
                                                <button type="submit" name="edit-donation-management" class="btn btn-warning btn-sm">Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="management_id" value="<?= $management['management_id'] ?>">
                                                <button type="submit" name="delete-donation-management" class="btn btn-danger btn-sm">Delete</button>
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
    </main>
</body>

</html>
