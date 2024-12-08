<?php

require_once '../../../controller/theme.php';

$error = "";
$theme = null;
$themeController = new ThemeController();

if (
    isset($_POST["titre"]) &&
    isset($_POST["description"]) &&
    isset($_FILES["image"]) // Utilisation de $_FILES pour récupérer le fichier
) {
    if (
        !empty($_POST["titre"]) &&
        !empty($_POST["description"]) &&
        !empty($_FILES["image"]["name"]) // Vérification si le fichier est téléchargé
    ) {
        // Récupération du fichier téléchargé
        $image = $_FILES['image'];

        // Liste des extensions d'images autorisées
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Récupérer l'extension du fichier téléchargé
        $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        // Vérifier si l'extension est valide
        if (in_array($fileExtension, $allowedExtensions)) {
            // Si l'extension est valide, on continue avec l'ajout du thème
            $theme = new theme(
                null,
                $_POST['titre'],
                $_POST['description'],
                $image['name'] // Vous pouvez aussi déplacer le fichier sur le serveur si nécessaire
            );

            $themeController->addTheme($theme);
            header('Location:bib.php');
            exit;
        } else {
            $error = "Le fichier téléchargé n'est pas une image valide. Extensions autorisées : .jpg, .jpeg, .png, .gif.";
        }
    } else {
        $error = "Informations manquantes.";
    }
}

?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2 ps" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Empreinte</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto ps ps--active-y" id="sidenav-collapse-main">
      <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.html">
            <i class="material-symbols-rounded opacity-5"></i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="bib.php">
            <i class="material-symbols-rounded opacity-5"></i>
            <span class="nav-link-text ms-1">Gestion theme</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps ps--active-y">
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-md-7 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <h6 class="mb-0">Theme Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
              <ul class="list-group">
                <form id="themeForm" action="" method="POST" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="booking-form-name">titre</label>
                    <input type="text" name="titre" class="form-control">
                    <div id="titre-error" class="error-message" style="color: red;"></div>
                  </div>
                  <div class="mb-3">
                    <label for="booking-form-email">description</label>
                    <input type="text" name="description" class="form-control">
                    <div id="description-error" class="error-message" style="color: red;"></div>
                  </div>
                  <div class="mb-3">
                    <label for="booking-form-number">image</label>
                    <input type="file" name="image" class="form-control">
                    <div id="image-error" class="error-message" style="color: red;">
                      <?php if ($error) echo $error; ?>
                    </div>
                  </div>
                  <button type="submit" name="add-donation" class="btn btn-primary">Ajouter ce theme</button>
                </form>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- JavaScript Validation -->
  <script>
    document.getElementById('themeForm').addEventListener('submit', function(e) {
        var titre = document.querySelector('input[name="titre"]');
        var description = document.querySelector('input[name="description"]');
        var image = document.querySelector('input[name="image"]');
        
        var titreError = document.getElementById('titre-error');
        var descriptionError = document.getElementById('description-error');
        var imageError = document.getElementById('image-error');
        
        // Clear previous error messages
        titreError.textContent = '';
        descriptionError.textContent = '';
        imageError.textContent = '';

        // Flag to track if there are any validation errors
        var hasError = false;

        // Validate titre
        if (titre.value.trim() === '') {
            titreError.textContent = 'Titre is required.';
            hasError = true;
        }

        // Validate description
        if (description.value.trim() === '') {
            descriptionError.textContent = 'Description is required.';
            hasError = true;
        }

        // Validate image
        if (image.value.trim() === '') {
            imageError.textContent = 'Image is required.';
            hasError = true;
        } else {
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            var fileExtension = image.value.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExtension)) {
                imageError.textContent = 'Image must be of type .jpg, .jpeg, .png, or .gif.';
                hasError = true;
            }
        }

        // If there are validation errors, prevent form submission
        if (hasError) {
            e.preventDefault();
        }
    });
  </script>

  <!-- JS Files -->
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
  <script async="" defer="" src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.