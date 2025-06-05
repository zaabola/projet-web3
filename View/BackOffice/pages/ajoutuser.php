<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Log session contents for debugging
error_log("Session content: " . print_r($_SESSION, true));

// Verify admin access
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'admin') {
  header("Location: ../../FrontOffice/logout.php");
  exit();
}

require_once '../../../Controller/userscontroller.php';

$usersController = new userscontroller();
$error = $success = "";

// Handle form submission using the PRG pattern
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add-user'])) {
  // Sanitize inputs
  $nom = trim($_POST['nom'] ?? '');
  $prenom = trim($_POST['prenom'] ?? '');
  $mdp = trim($_POST['mdp'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $type = trim($_POST['type'] ?? '');

  // Log form data for debugging
  error_log("Form data: " . print_r($_POST, true));

  // Validate inputs
  $validationErrors = [];
  if (empty($nom)) $validationErrors[] = "The 'Last Name' field is required.";
  if (empty($prenom)) $validationErrors[] = "The 'First Name' field is required.";
  if (empty($mdp)) $validationErrors[] = "The 'Password' field is required.";
  if (empty($email)) $validationErrors[] = "The 'Email' field is required.";
  if (empty($type)) $validationErrors[] = "The 'Role' field is required.";

  // Additional email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $validationErrors[] = "The email is not valid.";
  }

  // Check for duplicate email
  if (empty($validationErrors)) {
    $existingUser = $usersController->getUserByEmail($email);
    if ($existingUser) {
      $validationErrors[] = "This email is already in use.";
    }
  }

  if (empty($validationErrors)) {
    try {
      $user = new users(null, $nom, $prenom, $type, $mdp, $email);
      $usersController->addUser($user);

      // Redirect after successful addition to prevent form resubmission
      header("Location: ajoutuser.php?success=1");
      exit();
    } catch (Exception $e) {
      $error = "Error adding user: " . $e->getMessage();
      error_log("Exception in ajoutuser.php: " . $e->getMessage());
    }
  } else {
    $error = implode('<br>', $validationErrors);
  }
}

// Check if success flag is set in the query string
if (isset($_GET['success']) && $_GET['success'] == 1) {
  $success = "User added successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Add User</title>
  <link href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!-- Fonts and icons -->
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
      <a class="navbar-brand px-4 py-3 m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Emprunt</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="ajoutuser.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Add User</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Sales</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ReservationDashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Reservation Dashboard</span>
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
            <span class="nav-link-text ms-1">Delete Order</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="updatecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Update Order</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="fetchcommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Fetch Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Order Complaints</span>
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
            <span class="nav-link-text ms-1">Themes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reservation_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_reservation.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/ajoutbus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Add Bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/bus_tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Buses</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/edit_bus.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit Bus</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="liste.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="admin.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donations Manager</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="jointure.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Donors</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="test.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Edit Donations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Volunteers</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0"></div>
  </aside>

  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header">
              <h6>Add User</h6>
            </div>
            <div class="card-body">
              <!-- Success and Error Messages -->
              <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
              <?php endif; ?>
              <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
              <?php endif; ?>

              <!-- Add User Form -->
              <form method="POST">
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" name="nom" class="form-control" placeholder="Enter your last name" value="">
                </div>

                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" name="prenom" class="form-control" placeholder="Enter your first name" value="">
                </div>

                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="mdp" class="form-control" placeholder="Enter your password" value="">
                </div>

                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter your email" value="">
                </div>

                <div class="form-group">
                  <label>Role</label>
                  <select name="type" class="form-control">
                    <option value="" disabled <?= empty($_POST['type']) ? 'selected' : '' ?>>Choose role</option>
                    <option value="admin" <?= ($_POST['type'] ?? '') === "admin" ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= ($_POST['type'] ?? '') === "user" ? 'selected' : '' ?>>User</option>
                  </select>
                </div>

                <button type="submit" name="add-user" class="btn btn-success mt-3">Add</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>
