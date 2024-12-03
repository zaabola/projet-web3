<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'emprunt';
$username = 'root';
$password = '';

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
    $image = $_FILES['image']['name'] ?? '';

    // Si une nouvelle image est téléchargée
    if (!empty($image)) {
        $targetDir = "../../frontOfficeBib/";
        $targetFile = $targetDir . basename($image);

        // Vérification de l'extension du fichier
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        if (!in_array($imageFileType, $allowedExtensions)) {
            echo "<script>alert('Veuillez choisir un fichier avec une extension d\'image valide (jpg, jpeg, png, gif, bmp).');</script>";
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Mise à jour avec nouvelle image
                $updateQuery = "UPDATE articles 
                                SET Titre_article = :titre, 
                                    Description_article = :description, 
                                    Image_article = :image, 
                                    bibliographie = :bibliographie,
                                    date_maj = NOW() 
                                WHERE Id_article = :article_id";
                $stmt = $pdo->prepare($updateQuery);
                $stmt->bindParam(':titre', $titre);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image', $image);
                $stmt->bindParam(':bibliographie', $bibliographie);
                $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    } else {
        // Mise à jour sans changer l'image
        $updateQuery = "UPDATE articles 
                        SET Titre_article = :titre, 
                            Description_article = :description, 
                            bibliographie = :bibliographie,
                            date_maj = NOW() 
                        WHERE Id_article = :article_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':bibliographie', $bibliographie);
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
      var image = document.forms["articleForm"]["image"].value;
      
      var titreError = document.getElementById("titreError");
      var descriptionError = document.getElementById("descriptionError");
      var bibliographieError = document.getElementById("bibliographieError");
      var imageError = document.getElementById("imageError");
      
      titreError.innerHTML = "";
      descriptionError.innerHTML = "";
      bibliographieError.innerHTML = "";
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
      
      if (image != "") {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.bmp)$/i;
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
          
          <button type="submit" class="btn btn-success">Mettre à jour</button>
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
