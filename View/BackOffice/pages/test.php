<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id']) || $_SESSION['type'] == 'user') {
  // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
  header("Location: ../../FrontOffice/logout.php");
  exit();
}
include('../../../Controller/payement_don.php');

$donationController = new DonationController();
$success = $error = "";

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add-donation'])) {
    $firstName = htmlspecialchars($_POST['firstName'] ?? '');
    $lastName = htmlspecialchars($_POST['lastName'] ?? '');
    $cardNumber = htmlspecialchars($_POST['cardNumber'] ?? '');
    $expirationMonth = htmlspecialchars($_POST['expirationMonth'] ?? '');
    $expirationYear = htmlspecialchars($_POST['expirationYear'] ?? '');
    $cvc = htmlspecialchars($_POST['cvc'] ?? '');
    $country = htmlspecialchars($_POST['country'] ?? '');

    if (!empty($firstName) && !empty($lastName) && !empty($cardNumber) && !empty($expirationMonth) && !empty($expirationYear) && !empty($cvc) && !empty($country)) {
      $donationController->addDonation($firstName, $lastName, $cardNumber, $expirationMonth, $expirationYear, $cvc, $country);
      $success = "Donation ajoutée avec succès !";

      // Redirect after adding the donation to prevent resubmission on page refresh
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
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
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $cardNumber = htmlspecialchars($_POST['cardNumber']);
    $expirationMonth = htmlspecialchars($_POST['expirationMonth']);
    $expirationYear = htmlspecialchars($_POST['expirationYear']);
    $cvc = htmlspecialchars($_POST['cvc']);
    $country = htmlspecialchars($_POST['country']);

    if (!empty($firstName) && !empty($lastName) && !empty($cardNumber) && !empty($expirationMonth) && !empty($expirationYear) && !empty($cvc) && !empty($country)) {
      $donationController->updateDonation($id, $firstName, $lastName, $cardNumber, $expirationMonth, $expirationYear, $cvc, $country);
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
          <a class="nav-link text-dark" href="admin.php">
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
          <a class="nav-link active bg-gradient-dark text-white" href="test.php">
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
              <label for="firstName">First Name</label>
              <input type="text" name="firstName" class="form-control" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" />
            </div>
            <div class="mb-3">
              <label for="lastName">Last Name</label>
              <input type="text" name="lastName" class="form-control" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" />
            </div>
            <div class="mb-3">
              <label for="cardNumber">Card Number</label>
              <input type="text" name="cardNumber" class="form-control" value="<?= htmlspecialchars($_POST['cardNumber'] ?? '') ?>" />
            </div>
            <div class="mb-3">
              <label for="expirationMonth">Expiration Month</label>
              <select name="expirationMonth" class="form-control">
                <option value="">Month</option>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                  <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <?= ($_POST['expirationMonth'] ?? '') == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected' : '' ?>>
                    <?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>
                  </option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="expirationYear">Expiration Year</label>
              <select name="expirationYear" class="form-control">
                <option value="">Year</option>
                <?php for ($i = date("Y"); $i <= date("Y") + 20; $i++) : ?>
                  <option value="<?= $i; ?>" <?= ($_POST['expirationYear'] ?? '') == $i ? 'selected' : '' ?>>
                    <?= $i ?>
                  </option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="cvc">CVC</label>
              <input type="text" name="cvc" class="form-control" value="<?= htmlspecialchars($_POST['cvc'] ?? '') ?>" />
            </div>
            <div class="mb-3">
              <label for="country">Country</label>
              <select name="country" class="form-control">
                <option value="">Select Country</option>
                <?php
                $countries = ["Afghanistan", "Algeria", "Argentina", "Australia", "Belgium", "Brazil", "Canada", "China", "France", "Germany", "India", "Italy", "Japan", "Mexico", "Spain", "United Kingdom", "United States"];
                foreach ($countries as $country) : ?>
                  <option value="<?= $country ?>" <?= ($_POST['country'] ?? '') == $country ? 'selected' : '' ?>><?= $country ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" name="add-donation" class="btn btn-primary">Add Donation</button>
          </form>
        </div>

        <?php if (isset($donationToEdit)) : ?>
          <div class="col-md-6">
            <h4>Edit Donation</h4>
            <form action="" method="POST">
              <input type="hidden" name="id_donation" value="<?= $donationToEdit['id'] ?>">
              <div class="mb-3">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" class="form-control" value="<?= $donationToEdit['first_name'] ?>" />
              </div>
              <div class="mb-3">
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" class="form-control" value="<?= $donationToEdit['last_name'] ?>" />
              </div>
              <div class="mb-3">
                <label for="cardNumber">Card Number</label>
                <input type="text" name="cardNumber" class="form-control" value="<?= $donationToEdit['card_number'] ?>" />
              </div>
              <div class="mb-3">
                <label for="expirationMonth">Expiration Month</label>
                <select name="expirationMonth" class="form-control">
                  <option value="">Month</option>
                  <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <?= $donationToEdit['expiration_month'] == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected' : '' ?>>
                      <?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="expirationYear">Expiration Year</label>
                <select name="expirationYear" class="form-control">
                  <option value="">Year</option>
                  <?php for ($i = date("Y"); $i <= date("Y") + 20; $i++) : ?>
                    <option value="<?= $i; ?>" <?= $donationToEdit['expiration_year'] == $i ? 'selected' : '' ?>>
                      <?= $i ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="cvc">CVC</label>
                <input type="text" name="cvc" class="form-control" value="<?= $donationToEdit['cvc'] ?>" />
              </div>
              <div class="mb-3">
                <label for="country">Country</label>
                <select name="country" class="form-control">
                  <option value="">Select Country</option>
                  <?php
                  foreach ($countries as $country) : ?>
                    <option value="<?= $country ?>" <?= $donationToEdit['country'] == $country ? 'selected' : '' ?>><?= $country ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" name="update-donation" class="btn btn-primary">Update Donation</button>
            </form>
          </div>
        <?php endif; ?>

        <div class="col-md-12">
          <h4>Donation List</h4>
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Card Number</th>
                <th>Expiration Date</th>
                <th>Country</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donations as $donation) : ?>
                <tr>
                  <td><?= $donation['id'] ?></td>
                  <td><?= $donation['first_name'] ?></td>
                  <td><?= $donation['last_name'] ?></td>
                  <td><?= $donation['card_number'] ?></td>
                  <td><?= $donation['expiration_month'] . '/' . $donation['expiration_year'] ?></td>
                  <td><?= $donation['country'] ?></td>
                  <td>
                    <form action="" method="POST" style="display:inline;">
                      <input type="hidden" name="id_donation" value="<?= $donation['id'] ?>" />
                      <button type="submit" name="delete-donation" class="btn btn-danger">Delete</button>
                    </form>
                    <form action="" method="POST" style="display:inline;">
                      <input type="hidden" name="id_donation" value="<?= $donation['id'] ?>" />
                      <button type="submit" name="edit-donation" class="btn btn-warning">Edit</button>
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

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
