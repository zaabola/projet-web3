<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "empreinte1";
$username = "root";
$password = "";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

// Traitement de la suppression de feedback
if (isset($_GET['delete_feedback']) && is_numeric($_GET['delete_feedback'])) {
    $feedback_id = (int)$_GET['delete_feedback'];
    
    $deleteQuery = "DELETE FROM feed_back WHERE id_feed_back = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':id', $feedback_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Redirection pour rafraîchir la page
    header("Location: fb.php?id=" . $_GET['id']);
    exit;
}

// Récupération de l'ID de l'article
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID d'article invalide ou non spécifié.");
}
$article_id = (int)$_GET['id'];

// Récupération des informations de l'article
$articleQuery = "SELECT Titre_article FROM articles WHERE Id_article = :id";
$stmt = $pdo->prepare($articleQuery);
$stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Article non trouvé.");
}

// Récupération des feedbacks pour cet article
$feedbackQuery = "SELECT * FROM feed_back WHERE Id_article = :article_id ORDER BY id_feed_back ASC";
$stmt = $pdo->prepare($feedbackQuery);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pour le débogage
// echo "Article ID: " . $article_id . "<br>";
// echo "Nombre de feedbacks: " . count($feedbacks) . "<br>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Feedbacks - <?php echo htmlspecialchars($article['Titre_article']); ?></title>
    <link href="../assets/css/material-dashboard.min.css?v=3.2.0" rel="stylesheet">
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
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
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
      </ul>
    </div>
  </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Feedbacks pour l'article : <?php echo htmlspecialchars($article['Titre_article']); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php if (count($feedbacks) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Feedback</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $numero = 1;
                                            foreach ($feedbacks as $feedback): 
                                            ?>
                                                <tr>
                                                    <td><?php echo $numero++; ?></td>
                                                    <td><?php echo htmlspecialchars($feedback['commentaire']); ?></td>
                                                    <td>
                                                        <a href="?delete_feedback=<?php echo $feedback['id_feed_back']; ?>&id=<?php echo $article_id; ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce feedback ?')">
                                                            Supprimer
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center">Aucun feedback pour cet article.</p>
                            <?php endif; ?>
                            
                            <!-- Bouton de retour -->
                            <div class="mt-3">
                            <a href="javascript:history.back()" class="btn btn-primary">Retour</a>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>
</html>
