<?php
// Include required files
include('C:/xampp/htdocs/web/controller/donation_management.php');
include('C:/xampp/htdocs/web/controller/donation_controller.php');
include('C:/xampp/htdocs/web/vendor/autoload.php') ;
// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=emprunt', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
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
// Determine which table to display
$showManagementTable = false; 
$showDonationTable = false; 
if ($_SERVER['REQUEST_METHOD'] === 'POST')
 { $tableSelection = $_POST['table_selection'] ?? 'all';
     if ($tableSelection === 'all') { $showManagementTable = true; $showDonationTable = true; }
      elseif ($tableSelection === 'management') { $showManagementTable = true; } 
      elseif ($tableSelection === 'donation') { $showDonationTable = true; }
     }
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['generate_management_pdf'])) {
            // Generate PDF for Donation Management when button is clicked
            generateManagementPDF($uniqueDonationsManagement);
        }
    
        if (isset($_POST['generate_donations_pdf'])) {
            // Generate PDF for Donations when button is clicked
            generateDonationsPDF($donations);
        }
    }
    
    function generateManagementPDF($uniqueDonationsManagement) {
        // Create new TCPDF instance
        $pdf = new TCPDF();
    
        // Set PDF properties
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Donation Management Report');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
    
        // Set font
        $pdf->SetFont('helvetica', '', 12);
    
        // Add Donation Management Table
        $pdf->Cell(0, 10, 'Donation Management', 0, 1, 'C');
        $pdf->Ln(4); // Line break
    
        // Table headers
        $pdf->Cell(34, 10, 'Management ID', 1);
        $pdf->Cell(28, 10, 'Donation ID', 1);
        $pdf->Cell(30, 10, 'Donor Name', 1);
        $pdf->Cell(30, 10, 'Admin Name', 1);
        $pdf->Cell(40, 10, 'distribution_date', 1);
        $pdf->Ln();
    
        // Table data
        foreach ($uniqueDonationsManagement as $management) {
            $pdf->Cell(34, 10, $management['management_id'], 1);
            $pdf->Cell(28, 10, $management['id_donation'], 1);
            $pdf->Cell(30, 10, $management['donor_name'], 1);
            $pdf->Cell(30, 10, $management['admin_name'], 1);
            $pdf->Cell(40, 10, $management['distribution_date'], 1);
            $pdf->Cell(30, 10, $management['allocated_price'], 1);
            $pdf->Ln();
        }
    
        // Output the PDF
        $pdf->Output('Donation_Management_Report.pdf', 'I');
    }
    
    function generateDonationsPDF($donations) {
        // Create new TCPDF instance
        $pdf = new TCPDF();
    
        // Set PDF properties
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Donations Report');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
    
        // Set font
        $pdf->SetFont('helvetica', '', 12);
    
        // Add Donations Table
        $pdf->Cell(0, 10, 'Donations', 0, 1, 'C');
        $pdf->Ln(4); // Line break
    
        // Table headers
        $pdf->Cell(10, 10, 'ID', 1);
        $pdf->Cell(50, 10, 'Donor Name', 1);
        $pdf->Cell(70, 10, 'Email', 1); 
        $pdf->Cell(20, 10, 'Amount', 1);
        $pdf->Cell(40, 10, 'Message', 1);
        $pdf->Ln();
    
        // Table data
        foreach ($donations as $donation) {
            $pdf->Cell(10, 10, $donation['id_donation'], 1);
            $pdf->Cell(50, 10, $donation['donor_name'], 1);
            $pdf->Cell(70, 10, $donation['donor_email'], 1);
            $pdf->Cell(20, 10, $donation['donation_amount'], 1);
            $pdf->Cell(40, 10, $donation['message'], 1);
            $pdf->Ln();
        }
    
        // Output the PDF
        $pdf->Output('Donations_Report.pdf', 'I');
    }
?>

<!DOCTYPE html>
<html lang="en">
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd;
        }

        .table thead {
            background-color: #007bff;
            color: white;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .table-container {
            overflow-x: auto;
        }

        /* Add spacing between the tables */
        .table-container + .table-container {
            margin-top: 20px;
        }

        /* Button styles */
        .btn {
            padding: 10px 20px;
            font-size: 14px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body class="bg-gray-100">
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
                    <a class="nav-link active bg-gradient-dark text-white" href="liste.php">
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
                        <a class="nav-link active bg-gradient-dark text-white" href="jointure.php">
                        <i class="material-symbols-rounded opacity-5">table_view</i>
                        <span class="nav-link-text ms-1">Tableaux</span>
                    </a>
                    </li>
                
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps ps--active-x ps--active-y">
        
        <div class="container-fluid-py-2">
            <!-- Form for table selection -->
            <form method="POST" class="mb-4">
                <label for="table_selection" class="form-label">Select Table:</label>
                <select name="table_selection" id="table_selection" class="form-select">
                    <option value="all" <?= isset($_POST['table_selection']) && $_POST['table_selection'] === 'all' ? 'selected' : '' ?>>All Tables</option>
                    <option value="management" <?= isset($_POST['table_selection']) && $_POST['table_selection'] === 'management' ? 'selected' : '' ?>>Management Table</option>
                    <option value="donation" <?= isset($_POST['table_selection']) && $_POST['table_selection'] === 'donation' ? 'selected' : '' ?>>Donation Table</option>
                </select>
                <button type="submit" class="btn btn-primary mt-3">Display</button>
            </form>

            <!-- Management Table -->
            <?php if ($showManagementTable): ?>
                <h4>Donation Management</h4>
                <table class="table table-bordered">
        <thead>
            <tr>
                <th>management_id</th>
                <th>Donation ID</th>
                <th>Donor Name</th>
                <th>Admin Name</th>
                <th>Distribution Date</th>
                <th>Allocated Percentage</th>
                <th>Allocated Price</th>
    
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
            
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Donations Table -->
            <?php if ($showDonationTable): ?>
                <h4>Donations</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Donor Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Message</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?= htmlspecialchars($donation['id_donation']) ?></td>
                                <td><?= htmlspecialchars($donation['donor_name']) ?></td>
                                <td><?= htmlspecialchars($donation['donor_email']) ?></td>
                                <td><?= htmlspecialchars($donation['donation_amount']) ?></td>
                                <td><?= htmlspecialchars($donation['message']) ?></td>
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <form method="POST">
        <button type="submit" name="generate_management_pdf" class="btn btn-primary">Generate Donation Management PDF</button>
    </form>

    <!-- Button to generate Donations PDF -->
    <form method="POST">
        <button type="submit" name="generate_donations_pdf" class="btn btn-primary">Generate Donations PDF</button>
    </form>

    </main>
</body>
</html>