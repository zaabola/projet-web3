<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
// Connexion à la base de données
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";


try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Erreur de connexion : ' . $e->getMessage();
  exit;
}

// Vérification et récupération de l'ID de l'article
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID de l'article invalide ou non spécifié.");
}
$article_id = (int)$_GET['id'];

// Récupération de l'article à modifier
$articleQuery = "SELECT * FROM articles WHERE Id_article = :article_id";
$stmt = $pdo->prepare($articleQuery);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
  die("Article non trouvé.");
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_article'])) {
  $titre = $_POST['titre'] ?? '';
  $description = $_POST['description'] ?? '';
  $bibliographie = $_POST['bibliographie'] ?? '';
  $archivage = isset($_POST['archivage']) ? (int)$_POST['archivage'] : 0;
  $image = $_FILES['image']['name'] ?? '';

  // Si une nouvelle image est téléchargée
  if (!empty($image)) {
    $targetDir = "../../FrontOffice/images/";
    $targetFile = $targetDir . basename($image);

    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

    if (!in_array($imageFileType, $allowedExtensions)) {
      echo "<script>alert('Veuillez choisir un fichier avec une extension d\'image valide (jpg, jpeg, png, gif, bmp).');</script>";
    } else {
      if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $updateQuery = "UPDATE articles 
                                SET Titre_article = :titre, 
                                    Description_article = :description, 
                                    Image_article = :image, 
                                    bibliographie = :bibliographie,
                                    archivage = :archivage,
                                    date_maj = NOW() 
                                WHERE Id_article = :article_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':bibliographie', $bibliographie);
        $stmt->bindParam(':archivage', $archivage, PDO::PARAM_INT);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
      }
    }
  } else {
    $updateQuery = "UPDATE articles 
                        SET Titre_article = :titre, 
                            Description_article = :description, 
                            bibliographie = :bibliographie,
                            archivage = :archivage,
                            date_maj = NOW() 
                        WHERE Id_article = :article_id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':bibliographie', $bibliographie);
    $stmt->bindParam(':archivage', $archivage, PDO::PARAM_INT);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
  }

  header("Location: artc.php?id=" . $article['id']);

  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Modification de l'Article</title>
  <link href="../assets/css/material-dashboard.min.css?v=3.2.0" rel="stylesheet">
  <style>
    .error {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
  </style>
  <script>
    function validateForm() {
      var isValid = true;

      var titre = document.forms["articleForm"]["titre"].value;
      var description = document.forms["articleForm"]["description"].value;
      var bibliographie = document.forms["articleForm"]["bibliographie"].value;
      var archivage = document.forms["articleForm"]["archivage"].value;
      var image = document.forms["articleForm"]["image"].value;

      var titreError = document.getElementById("titreError");
      var descriptionError = document.getElementById("descriptionError");
      var bibliographieError = document.getElementById("bibliographieError");
      var archivageError = document.getElementById("archivageError");
      var imageError = document.getElementById("imageError");

      titreError.innerHTML = "";
      descriptionError.innerHTML = "";
      bibliographieError.innerHTML = "";
      archivageError.innerHTML = "";
      imageError.innerHTML = "";

      if (titre == "") {
        titreError.innerHTML = "Le titre est obligatoire.";
        isValid = false;
      }

      if (description == "") {
        descriptionError.innerHTML = "La description est obligatoire.";
        isValid = false;
      }

      if (bibliographie.length > 500) {
        bibliographieError.innerHTML = "La bibliographie ne doit pas dépasser 500 caractères.";
        isValid = false;
      }

      if (archivage != 0 && archivage != 1) {
        archivageError.innerHTML = "La valeur de l'archivage doit être 0 ou 1.";
        isValid = false;
      }

      if (image != "") {
        var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.bmp)$/i;
        if (!allowedExtensions.exec(image)) {
          imageError.innerHTML = "Veuillez choisir un fichier avec une extension d'image valide.";
          isValid = false;
        }
      }

      return isValid;
    }
  </script>
</head>




<body>
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
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps">
    <div class="container-fluid py-2">
      <h3>Modifier l'article</h3>
      <div class="card mb-4">
        <div class="card-header">
          <h6>Modifier l'article</h6>
        </div>
        <div class="card-body">
          <form name="articleForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="update_article" value="1">

            <div class="form-group">
              <label for="titre">Titre de l'article</label>
              <input type="text" name="titre" class="form-control" value="<?php echo htmlspecialchars($article['Titre_article']); ?>">
              <div id="titreError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="description">Description de l'article</label>
              <textarea name="description" class="form-control"><?php echo htmlspecialchars($article['Description_article']); ?></textarea>
              <div id="descriptionError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="bibliographie">Bibliographie</label>
              <textarea name="bibliographie" class="form-control"><?php echo htmlspecialchars($article['bibliographie']); ?></textarea>
              <div id="bibliographieError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="image">Image de l'article</label>
              <input type="file" name="image" class="form-control" accept="image/*">
              <div id="imageError" class="error"></div>
              <p>Image actuelle : <img src="../../frontOfficeBib/<?php echo htmlspecialchars($article['Image_article']); ?>" alt="Image de l'article" width="100"></p>
            </div>

            <div class="mb-3">
              <label for="archivage">Archivage (0 pour archivé, 1 pour actif)</label>
              <input type="number"
                name="archivage"
                id="archivage"
                class="form-control"
                min="0"
                max="1"
                value="<?php echo htmlspecialchars($article['archivage']); ?>"
                onchange="validateArchivage(this)"
                required>
              <div id="archivageError" class="text-danger"></div>
            </div>

            <button type="submit" class="btn btn-success" name="update_article">Mettre à jour</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
