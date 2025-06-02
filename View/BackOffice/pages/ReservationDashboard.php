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
// Include the database connection file
require_once '../../../config.php';

try {
  // Get the database connection
  $db = Config::getConnexion();

  // Query to get reservation statistics by destination
  $reservationQuery = $db->query("
        SELECT destination, COUNT(*) AS total_reservations 
        FROM reservation 
        GROUP BY destination
    ");
  $reservationsData = $reservationQuery->fetchAll();

  // Query to get bus statistics by destination
  $busQuery = $db->query("
        SELECT destination, COUNT(*) AS total_buses 
        FROM bus 
        GROUP BY destination
    ");
  $busesData = $busQuery->fetchAll();
} catch (Exception $e) {
  die('Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Reservation Dashboard</title>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/ReservationDashboard.php">
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
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">Check the reservation and bus statistics.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <h3 class="text-center">Reservation Statistics</h3>
          <canvas id="reservationChart" height="200"></canvas>
        </div>
        <div class="col-md-6">
          <h3 class="text-center">Bus Statistics</h3>
          <canvas id="busChart" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-4">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <script>
                document.write(new Date().getFullYear())
              </script>, made <i class="fa fa-heart"></i> by <a href="../../FrontOffice/index.php" target="_blank">Emprunt</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <a href="../../FrontOffice/index.php" class="nav-link text-muted" target="_blank">About Us</a>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </main>

  <script>
    // Reservation data (PHP to JavaScript)
    const reservationData = <?php echo json_encode($reservationsData); ?>;
    const reservationLabels = reservationData.map(item => item.destination);
    const reservationCounts = reservationData.map(item => item.total_reservations);

    // Bus data (PHP to JavaScript)
    const busData = <?php echo json_encode($busesData); ?>;
    const busLabels = busData.map(item => item.destination);
    const busCounts = busData.map(item => item.total_buses);

    // Reservation Chart
    const reservationCtx = document.getElementById("reservationChart").getContext("2d");
    new Chart(reservationCtx, {
      type: "bar",
      data: {
        labels: reservationLabels,
        datasets: [{
          label: "Reservations",
          data: reservationCounts,
          backgroundColor: "rgba(75, 192, 192, 0.6)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1 // Adjusts the scale to increment by 1
            }
          }
        }
      }
    });

    // Bus Chart
    const busCtx = document.getElementById("busChart").getContext("2d");
    new Chart(busCtx, {
      type: "bar",
      data: {
        labels: busLabels,
        datasets: [{
          label: "Buses",
          data: busCounts,
          backgroundColor: "rgba(153, 102, 255, 0.6)",
          borderColor: "rgba(153, 102, 255, 1)",
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1 // Adjusts the scale to increment by 1
            }
          }
        }
      }
    });
  </script>

  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
</body>

</html>
