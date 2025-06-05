<?php
include '../../../Controller/volntaireC.php';

$PortfolioC = new PortfolioC();

if (isset($_GET['delete'])) {
  $PortfolioC->delete($_GET['delete']);
} else if (isset($_GET['update'])) {
  $p = new Portfolio(
    $_POST['nom'],       // Last Name
    $_POST['prenom'],    // First Name
    $_FILES['photo']['name'], // Photo (assumes the file name is used for storage)
    $_POST['langue'],    // Language
    $_POST['specialite'], // Specialty
    $_POST['biographie'] // Biography
  );
  $p->setId_portfolio($_GET['update']);
  $PortfolioC->update($p);
} else {
  $ps = $PortfolioC->findone2($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Go Back</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/portfolio.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Portfolio</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="p-4">
      <?php
      foreach ($ps as $p) :
      ?>
        <h1>Edit Portfolio</h1>
        <form action="?update=<?= $p['id_portfolio'] ?>" method="post" id="portfolioForm" class="custom-form contact-form" role="form" enctype="multipart/form-data">

          <!-- Last Name -->
          <div class="input-group input-group-static mb-4">
            <p>Last Name</p>
            <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($p['nom']) ?>">
            <span id="nomError"></span>
          </div>

          <!-- First Name -->
          <div class="input-group input-group-static mb-4">
            <p>First Name</p>
            <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($p['prenom']) ?>">
            <span id="prenomError"></span>
          </div>

          <!-- Photo -->
          <div class="input-group input-group-static mb-4">
            <p>Photo</p>
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            <?php if (!empty($p['photo'])): ?>
              <img src="images/<?= htmlspecialchars($p['photo']) ?>" alt="Photo of <?= htmlspecialchars($p['nom']) ?>" style="width: 100px; height: 100px; margin-top: 10px;">
            <?php endif; ?>
            <span id="photoError"></span>
          </div>

          <!-- Language -->
          <div class="input-group input-group-static mb-4">
            <p>Language</p>
            <input type="text" name="langue" id="langue" class="form-control" value="<?= htmlspecialchars($p['langue']) ?>">
            <span id="langueError"></span>
          </div>

          <!-- Specialty -->
          <div class="input-group input-group-static mb-4">
            <p>Specialty</p>
            <input type="text" name="specialite" id="specialite" class="form-control" value="<?= htmlspecialchars($p['specialite']) ?>">
            <span id="specialiteError"></span>
          </div>

          <!-- Biography -->
          <div class="input-group input-group-static mb-4">
            <p>Biography</p>
            <textarea name="biographie" id="biographie" rows="4" class="form-control"><?= htmlspecialchars($p['biographie']) ?></textarea>
            <span id="biographieError"></span>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-success">Update</button>
          <a href="?delete=<?= $p['id_portfolio'] ?>" class="btn btn-danger">Delete</a>
          <a href="tables.php" class="btn btn-primary">Cancel</a>
        </form>
      <?php
      endforeach;
      ?>
      <script>
        document.getElementById('portfolioForm').addEventListener('submit', function(e) {
          let isValid = true;

          // Regex for text fields
          const textRegex = /^[a-zA-Z-\s]+$/;

          // Get all input values
          let nom = document.getElementById('nom');
          let prenom = document.getElementById('prenom');
          let photo = document.getElementById('photo');
          let langue = document.getElementById('langue');
          let specialite = document.getElementById('specialite');
          let biographie = document.getElementById('biographie');

          // Clear error messages
          document.querySelectorAll('span').forEach(span => span.innerHTML = "");

          // Validate last name
          if (nom.value.trim() === '') {
            document.getElementById('nomError').innerHTML = "The last name field is required.";
            isValid = false;
          } else if (!textRegex.test(nom.value)) {
            document.getElementById('nomError').innerHTML = "The last name must contain only letters and hyphens.";
            isValid = false;
          }

          // Validate first name
          if (prenom.value.trim() === '') {
            document.getElementById('prenomError').innerHTML = "The first name field is required.";
            isValid = false;
          } else if (!textRegex.test(prenom.value)) {
            document.getElementById('prenomError').innerHTML = "The first name must contain only letters and hyphens.";
            isValid = false;
          }

          // Validate photo
          if (photo.files.length === 0) {
            // Only show this error if there's no existing photo
            if (!<?= json_encode(!empty($p['photo'])) ?>) {
              document.getElementById('photoError').innerHTML = "Please upload a photo.";
              isValid = false;
            }
          }

          // Validate language
          if (langue.value.trim() === '') {
            document.getElementById('langueError').innerHTML = "The language field is required.";
            isValid = false;
          }

          // Validate specialty
          if (specialite.value.trim() === '') {
            document.getElementById('specialiteError').innerHTML = "The specialty field is required.";
            isValid = false;
          }

          // Validate biography
          if (biographie.value.trim() === '') {
            document.getElementById('biographieError').innerHTML = "The biography field is required.";
            isValid = false;
          }

          // Prevent form submission if validation fails
          if (!isValid) e.preventDefault();
        });
      </script>

  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-symbols-rounded">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark active" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2 active ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Core JS Files -->
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
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
