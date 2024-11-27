<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'Emprunt';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

// Récupération de l'ID du thème
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de thème invalide ou non spécifié.");
}
$theme_id = (int)$_GET['id'];

// Récupération du titre du thème
$themeQuery = "SELECT titre FROM theme WHERE id = :id";
$stmt = $pdo->prepare($themeQuery);
$stmt->bindParam(':id', $theme_id, PDO::PARAM_INT);
$stmt->execute();
$theme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$theme) {
    die("Thème non trouvé.");
}

// Traitement du formulaire d'ajout d'article
$errorMessages = [
    'titre' => '',
    'description' => '',
    'image' => ''
];
$successMessage = '';
$formVisible = true;  // Le formulaire est visible par défaut

// Initialisation des variables du formulaire
$titre = '';
$description = '';
$image = '';

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    // Validation des champs
    $valid = true;

    // Vérification du titre
    if (empty($titre)) {
        $errorMessages['titre'] = "Le titre est requis.";
        $valid = false;
    }

    // Vérification de la description
    if (empty($description)) {
        $errorMessages['description'] = "La description est requise.";
        $valid = false;
    }

    // Vérification de l'image
    if (empty($image)) {
        $errorMessages['image'] = "L'image est requise.";
        $valid = false;
    } else {
        // Vérification de l'extension de l'image
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, $allowedExtensions)) {
            $errorMessages['image'] = "L'image doit être au format .jpg, .jpeg, .png, ou .gif.";
            $valid = false;
        }
    }

    // Si aucune erreur, on procède à l'ajout de l'article
    if ($valid) {
        $targetDir = "../../frontOfficeBib/";
        $targetFile = $targetDir . basename($image);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $insertQuery = "INSERT INTO articles (Titre_article, Description_article, Image_article, id) 
                            VALUES (:titre, :description, :image, :theme_id)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
            $stmt->execute();

            // Message de succès
            $successMessage = "Article ajouté avec succès!";
            // Masquer le formulaire après ajout
            $formVisible = false; 
            // Réinitialisation des champs pour un nouvel ajout
            $titre = '';
            $description = '';
            $image = '';
        } else {
            $errorMessages['image'] = "Erreur lors du téléchargement de l'image.";
        }
    }
}

// Traitement de la suppression d'article
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Suppression de l'article
    $deleteQuery = "DELETE FROM articles WHERE Id_article = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirection après suppression
    header("Location: artc.php?id=$theme_id");
    exit;
}

// Récupération des articles liés au thème
$query = "SELECT * FROM articles WHERE id = :theme_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gestion des Articles - <?php echo htmlspecialchars($theme['titre']); ?></title>
  <link href="../assets/css/material-dashboard.min.css?v=3.2.0" rel="stylesheet">
</head>

<body class="g-sidenav-show bg-gray-100">
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps">
  <div class="container-fluid py-2">
    <h3>Articles du Thème : <?php echo htmlspecialchars($theme['titre']); ?></h3>

    <!-- Bouton pour afficher le formulaire -->
    <button class="btn btn-success mb-4" onclick="toggleForm()">Ajouter un article</button>

    <!-- Formulaire d'ajout d'article (disparaît après un ajout réussi) -->
    <div class="card mb-4" id="addArticleForm" style="display: <?php echo $formVisible ? 'block' : 'none'; ?>;">
      <div class="card-header">
        <h6>Ajouter un nouvel article</h6>
      </div>
      <div class="card-body">
        <?php if ($successMessage) : ?>
          <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="add_article" value="1">
          
          <div class="form-group">
            <label for="titre">Titre de l'article</label>
            <input type="text" name="titre" id="titre" class="form-control" value="<?php echo htmlspecialchars($titre ?? ''); ?>">
            <?php if ($errorMessages['titre']) : ?>
              <div class="text-danger"><?php echo $errorMessages['titre']; ?></div>
            <?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="description">Description de l'article</label>
            <textarea name="description" id="description" class="form-control"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            <?php if ($errorMessages['description']) : ?>
              <div class="text-danger"><?php echo $errorMessages['description']; ?></div>
            <?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="image">Image de l'article</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <?php if ($errorMessages['image']) : ?>
              <div class="text-danger"><?php echo $errorMessages['image']; ?></div>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-success">Ajouter</button>
        </form>
      </div>
    </div>

    <!-- Tableau des articles -->
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($articles)): ?>
            <tr>
              <td colspan="4">Aucun article trouvé pour ce thème.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($articles as $article): ?>
              <tr>
                <td><?php echo htmlspecialchars($article['Titre_article']); ?></td>
                <td><?php echo htmlspecialchars($article['Description_article']); ?></td>
                <td>
                  <?php if (!empty($article['Image_article'])): ?>
                    <img src="../../frontOfficeBib/<?php echo htmlspecialchars($article['Image_article']); ?>" alt="Image de l'article" width="100">
                  <?php else: ?>
                    Pas d'image
                  <?php endif; ?>
                </td>
                <td>
                  <a href="updatearticle.php?id=<?php echo $article['Id_article']; ?>" class="btn btn-primary">Modifier</a>
                  <a href="?delete_id=<?php echo $article['Id_article']; ?>&id=<?php echo $theme_id; ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?')">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script>
  // Fonction pour afficher/masquer le formulaire
  function toggleForm() {
    const form = document.getElementById("addArticleForm");
    form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
  }
</script>
</body>
</html>
