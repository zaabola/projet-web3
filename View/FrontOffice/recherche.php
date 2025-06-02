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
// Connexion à la base de données
$host = 'localhost';
$dbname = 'emprunt';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}

// Récupération du terme de recherche
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($searchTerm)) {
  // Recherche dans les articles et les thèmes
  $query = "SELECT 
                articles.Id_article,
                articles.Titre_article,
                articles.Description_article,
                articles.Image_article,
                articles.bibliographie,
                theme.id as theme_id,
                theme.titre as theme_titre
              FROM articles 
              LEFT JOIN theme ON articles.id = theme.id
              WHERE 
                articles.Titre_article LIKE :search 
                OR articles.Description_article LIKE :search
                OR articles.bibliographie LIKE :search
                OR theme.titre LIKE :search
                AND articles.archivage = 1";  // Ajout de cette condition


  $stmt = $pdo->prepare($query);
  $searchPattern = "%{$searchTerm}%";
  $stmt->bindParam(':search', $searchPattern);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Résultats de recherche</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-icons.css" rel="stylesheet">
  <link href="css/vegas.min.css" rel="stylesheet">
  <link href="css/tooplate-barista.css" rel="stylesheet">

  <style>
    :root {
      --white-color: #e6dede;
      --primary-color: #BC6C25;
      --secondary-color: #DDA15E;
      --section-bg-color: #b78752;
      --custom-btn-bg-color: #BC6C25;
      --custom-btn-bg-hover-color: #DDA15E;
      --dark-color: #000000;
      --p-color: #717275;
      --border-color: #7fffd4;
      --link-hover-color: #E76F51;

      --body-font-family: 'Plus Jakarta Sans', sans-serif;

      --h1-font-size: 68px;
      --h2-font-size: 46px;
      --h3-font-size: 32px;
      --h4-font-size: 28px;
      --h5-font-size: 24px;
      --h6-font-size: 22px;
      --p-font-size: 20px;
      --btn-font-size: 16px;
      --form-btn-font-size: 18px;
      --menu-font-size: 16px;

      --border-radius-large: 100px;
      --border-radius-medium: 20px;
      --border-radius-small: 10px;

      --font-weight-thin: 200;
      --font-weight-light: 300;
      --font-weight-normal: 400;
      --font-weight-bold: 700;
    }

    body {
      background-color: var(--white-color);
      font-family: var(--body-font-family);
    }

    .search-result {
      background-color: #fff;
      border-radius: var(--border-radius-small);
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-custom {
      background-color: var(--custom-btn-bg-color);
      color: var(--white-color);
      font-size: var(--btn-font-size);
      padding: 10px 20px;
      border-radius: var(--border-radius-small);
      border: none;
    }

    .btn-custom:hover {
      background-color: var(--custom-btn-bg-hover-color);
      color: var(--white-color);
    }

    h2 {
      color: var(--primary-color);
      font-size: var(--h2-font-size);
    }

    h3 {
      color: var(--secondary-color);
      font-size: var(--h3-font-size);
    }
  </style>
</head>

<body>
  <main>
    <div class="container mt-4">
      <h2 class="mb-4">Résultats de recherche pour "<?php echo htmlspecialchars($searchTerm); ?>"</h2>

      <!-- Formulaire de recherche -->
      <div class="row justify-content-center mb-4">
        <div class="col-md-6">
          <form action="recherche.php" method="GET">
            <div class="input-group">
              <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
              <button type="submit" class="btn btn-custom">Rechercher</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Résultats -->
      <div class="row">
        <?php if (!empty($searchTerm)): ?>
          <?php if (!empty($results)): ?>
            <?php foreach ($results as $result): ?>
              <div class="col-md-6 mb-4">
                <div class="search-result">
                  <h3><?php echo htmlspecialchars($result['Titre_article']); ?></h3>
                  <p><strong>Thème :</strong> <?php echo htmlspecialchars($result['theme_titre']); ?></p>
                  <p><?php echo htmlspecialchars($result['Description_article']); ?></p>
                  <?php if (!empty($result['Image_article'])): ?>
                    <img src="<?php echo htmlspecialchars($result['Image_article']); ?>"
                      alt="Image de l'article"
                      class="img-fluid mb-2"
                      style="max-height: 200px;">
                  <?php endif; ?>
                  <p><strong>Bibliographie :</strong> <?php echo htmlspecialchars($result['bibliographie']); ?></p>
                  <a href="affichage.php?theme_id=<?php echo $result['theme_id']; ?>"
                    class="btn btn-custom">Voir le thème complet</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <p class="text-center">Aucun résultat trouvé pour votre recherche.</p>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Bouton retour -->
      <div class="text-center mt-4">
        <a href="index1.php" class="btn btn-custom">Retour à l'accueil</a>
      </div>
    </div>

  </main>

  <!-- Scripts -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/click-scroll.js"></script>
  <script src="js/vegas.min.js"></script>
  <script src="js/custom.js"></script>
</body>

</html>
