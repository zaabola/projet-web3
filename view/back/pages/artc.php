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
    'image' => '',
    'bibliographie' => ''
];
$successMessage = '';
$formVisible = false;  // Le formulaire est visible par défaut

// Initialisation des variables du formulaire
$titre = '';
$description = '';
$image = '';
$bibliographie = '';

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_FILES['image']['name'] ?? '';
    $bibliographie = $_POST['bibliographie'] ?? '';

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
            $insertQuery = "INSERT INTO articles (Titre_article, Description_article, Image_article, bibliographie, id, date_crt, date_maj) 
                            VALUES (:titre, :description, :image, :bibliographie, :theme_id, NOW(), NOW())";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':bibliographie', $bibliographie);
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
            $bibliographie = '';
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

// Récupération des articles liés au thème avec leurs feedbacks
$query = "SELECT DISTINCT articles.* 
          FROM articles 
          WHERE articles.id = :theme_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Au début du fichier, après la connexion à la base de données
// Requête pour obtenir le nombre de feedbacks par article pour ce thème spécifique
$queryFeedbacks = "SELECT articles.Titre_article, COUNT(feed_back.Id_article) as nb_feedbacks 
                   FROM articles 
                   LEFT JOIN feed_back ON articles.Id_article = feed_back.Id_article 
                   WHERE articles.id = :theme_id
                   GROUP BY articles.Id_article 
                   ORDER BY nb_feedbacks DESC 
                   LIMIT 5";

$stmtFeedbacks = $pdo->prepare($queryFeedbacks);
$stmtFeedbacks->bindParam(':theme_id', $theme_id);
$stmtFeedbacks->execute();
$feedbackData = $stmtFeedbacks->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$labels = [];
$data = [];
foreach ($feedbackData as $item) {
    $labels[] = $item['Titre_article'];
    $data[] = $item['nb_feedbacks'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Gestion des Articles - <?php echo htmlspecialchars($theme['titre']); ?></title>
  <link href="../assets/css/material-dashboard.min.css?v=3.2.0" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps">
  <div class="container-fluid py-2">
    
    <h3>Articles du Thème : <?php echo htmlspecialchars($theme['titre']); ?></h3>

    <!-- Bouton pour afficher le formulaire -->
    <button class="btn btn-success mb-4" onclick="toggleForm()">Ajouter un article</button>

    <!-- Formulaire d'ajout d'article -->
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

          <div class="form-group">
            <label for="bibliographie">Bibliographie de l'article</label>
            <textarea name="bibliographie" id="bibliographie" class="form-control"><?php echo htmlspecialchars($bibliographie ?? ''); ?></textarea>
            <?php if ($errorMessages['bibliographie']) : ?>
              <div class="text-danger"><?php echo $errorMessages['bibliographie']; ?></div>
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
            <th>Bibliographie</th>
            <th>Archivé</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articles as $article) : ?>
            <tr>
              <td><?php echo htmlspecialchars($article['Titre_article']); ?></td>
              <td><?php echo htmlspecialchars($article['Description_article']); ?></td>
              <td><img src="../../frontOfficeBib/<?php echo htmlspecialchars($article['Image_article']); ?>" alt="Image" width="100"></td>
              <td><?php echo htmlspecialchars($article['bibliographie']); ?></td>
              <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="article_id" value="<?php echo $article['Id_article']; ?>">
                        <input type="hidden" name="toggle_archive" value="1">
                        <button type="submit" class="btn btn-<?php echo $article['archivage'] ? 'warning' : 'success'; ?>">
                            <?php echo $article['archivage'] ? 'Actif' : 'Archivé'; ?>
                        </button>
                    </form>
                </td>
              <td>
                <a href="updatearticle.php?id=<?php echo $article['Id_article']; ?>" class="btn btn-warning">Modifier</a>
                <a href="?delete_id=<?php echo $article['Id_article']; ?>&id=<?php echo $theme_id; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</a>
                <a href="fb.php?id=<?php echo $article['Id_article']; ?>" class="btn btn-info">Gérer feedbacks</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    

    <!-- Ajouter après votre tableau d'articles -->
    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Articles les plus commentés de ce thème</h6>
                </div>
                <div class="card-body">
                    <canvas id="feedbackChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <a href="bib.php" class="btn btn-primary mb-3">Retour aux thèmes</a>

  </div>
</main>

<!-- Scripts -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

<script>
  // Fonction pour afficher/masquer le formulaire
  function toggleForm() {
    const form = document.getElementById('addArticleForm');
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
  }

  const ctx = document.getElementById('feedbackChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Nombre de feedbacks',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: [
                '#BC6C25',
                '#DDA15E',
                '#b78752',
                '#a36d46',
                '#8b5e3c'
            ],
            borderColor: [
                '#BC6C25',
                '#DDA15E',
                '#b78752',
                '#a36d46',
                '#8b5e3c'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Nombre de feedbacks par article',
                color: '#333',
                font: {
                    size: 16
                }
            }
        }
    }
  });
</script>
</body>
</html>