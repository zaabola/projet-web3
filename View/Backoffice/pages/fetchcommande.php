<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$dbname = "empreinte1";

try {
    // Establish the database connection
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Determine the total number of commandes
try {
    $sql = "SELECT COUNT(*) FROM commande";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $total_commandes = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error fetching total commandes: " . $e->getMessage());
}

// Set the number of commandes per page and calculate the total number of pages
$commandes_per_page = 5;
$total_pages = ceil($total_commandes / $commandes_per_page);

// Get the current page from the query parameter; default to 1 if not present
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the OFFSET for the SQL query
$offset = ($current_page - 1) * $commandes_per_page;

// Fetch commandes for the current page
try {
    $sql = "SELECT * FROM commande LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':limit', $commandes_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all rows as an associative array
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching commandes: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Material Dashboard 3 by Creative Tim</title>
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
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/tables.php">
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
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/fetchcommande.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">fetchOrders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="reclamation.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Complaints</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/produit.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Products</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header pb-0">
              <h6>All Commandes</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead style="background-color: black">
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Id_commande</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Adresse_client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Tel_client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Nom_client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Prenom_client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Num_Panier</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($commandes)): ?>
                      <?php foreach ($commandes as $commande): ?>
                        <tr>
                          <td><?= htmlspecialchars($commande['Id_commande']) ?></td>
                          <td><?= htmlspecialchars($commande['Adresse_client']) ?></td>
                          <td><?= htmlspecialchars($commande['Tel_client']) ?></td>
                          <td><?= htmlspecialchars($commande['Nom_client']) ?></td>
                          <td><?= htmlspecialchars($commande['Prenom_client']) ?></td>
                          <td><?= htmlspecialchars($commande['id_panier']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="6">No commandes found.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <style>
.pagination {
    display: flex;
    justify-content: center;
    padding: 20px 0;
}

.pagination a {
    margin: 0 5px;
    padding: 10px 15px;
    text-decoration: none;
    color: #000; /* Black text color */
    border: 1px solid #000; /* Black border color */
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.pagination a:hover {
    background-color: #000; /* Black background color on hover */
    color: #fff; /* White text color on hover */
}

.pagination a.active {
    background-color: #000; /* Black background color for active page */
    color: #fff; /* White text color for active page */
    pointer-events: none;
}
</style>

              <!-- Pagination controls -->
              <div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="?page=<?= $current_page - 1 ?>" class="pagination-btn">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?page=<?= $current_page + 1 ?>" class="pagination-btn">Next</a>
    <?php endif; ?>
</div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

