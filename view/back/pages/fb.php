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