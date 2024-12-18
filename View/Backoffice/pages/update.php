<?php
session_start();
require_once('session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id'])) {
    // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
    header("Location: ../FrontOffice/login.php");
    exit();
}
include_once '../../../Controller/theme.php';

$error = "";
$imageError = "";
$offer = null;

// Create an instance of the controller
$offerController = new ThemeController();

if (isset($_POST["titre"]) && isset($_POST["description"]) && isset($_FILES["image"])) {
    if (!empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_FILES["image"]["name"])) {
        
        // Vérification de l'extension de l'image
        $imageName = $_FILES["image"]["name"];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        
        // Vérifie si l'extension du fichier est valide
        if (!in_array(strtolower($imageExtension), $allowedExtensions)) {
            $imageError = "Le fichier doit être une image avec une extension valide (jpg, jpeg, png, gif).";
        } else {
            // Si l'extension est valide, poursuivre la mise à jour du thème
            $disponible = isset($_POST['disponible']) ? true : false;
            $offer = new theme(
                null,
                $_POST['titre'],
                $_POST['description'],
                $_FILES['image']['name'] // Nom du fichier image
            );
            $offerController->updateTheme($offer, $_GET['id']);
            header('Location:bib.php');
            exit;
        }
    } else {
        $error = "Il manque des informations.";
    }
}
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Material Dashboard 3 by Creative Tim</title>
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">
  <script>
    // Function to sanitize input fields (remove HTML tags and special characters)
    function sanitizeInput(input) {
      return input.replace(/<[^>]*>/g, '').replace(/[^\w\s]/gi, '');
    }

    // Function to handle form submission
    function validateForm() {
      var titre = document.getElementById('titre').value;
      var description = document.getElementById('description').value;
      var image = document.getElementById('image').value;

      // Sanitize inputs
      document.getElementById('titre').value = sanitizeInput(titre);
      document.getElementById('description').value = sanitizeInput(description);

      // Check if the required fields are empty after sanitization
      if (titre == '' || description == '' || image == '') {
        alert('All fields must be filled!');
        return false;
      }

      // Form is valid
      return true;
    }
  </script>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2 ps">
    <!-- Sidebar content -->
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Emreinte</span>
      </a>
    </div>

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
          <a class="nav-link text-dark" href="tables.php">
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
        <a class="nav-link active bg-gradient-dark text-white" href="bib.php">
            <i class="material-symbols-rounded opacity-5"></i>
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
            <span class="nav-link-text ms-1">Modification des reservations</span>
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
        
        
        
        
        
      </ul>

  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps">
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-md-7 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <h6 class="mb-0">modification theme</h6>
            </div>
            <div class="card-body pt-4 p-3">
              <ul class="list-group">
                <div class="row no-gutters align-items-center">
                  <?php
                  if (isset($_GET['id'])) {
                      $offer = $offerController->getThemeById($_GET['id']);
                  ?>
                  <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="mb-3">
                      <label for="titre">Titre</label>
                      <input type="text" name="titre" id="titre" class="form-control" value="<?php echo $offer['titre']; ?>">
                    </div>
                    <div class="mb-3">
                      <label for="description">Description</label>
                      <input type="text" name="description" id="description" class="form-control" value="<?php echo $offer['description']; ?>">
                    </div>
                    <div class="mb-3">
                      <label for="image">Image</label>
                      <input type="file" name="image" id="image" class="form-control" value="<?php echo $offer['image']; ?>">
                      <?php
                      if ($imageError) {
                          echo "<p style='color:red;'>$imageError</p>";
                      }
                      ?>
                    </div>
                    <button type="submit" name="add-donation" class="btn btn-primary">Edit</button>
                  </form>
                  <?php
                  }
                  ?>
                </div>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>
</html>
