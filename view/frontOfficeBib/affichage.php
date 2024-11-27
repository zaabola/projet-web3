<?php
// Inclure les fichiers nécessaires
include_once(__DIR__ . '../../../controller/Articlee.php');
include_once(__DIR__ . '../../../controller/theme.php');

// Instancier les contrôleurs
$articlesController = new ArticlesController();
$themeController = new ThemeController();

// Vérifier si 'theme_id' est présent dans l'URL
if (isset($_GET['theme_id'])) {
    $theme_id = $_GET['theme_id'];

    // Récupérer les informations du thème avec l'ID
    $theme = $themeController->getThemeById($theme_id);

    // Récupérer les articles associés à ce thème
    $articles = $articlesController->getArticlessByTheme($theme_id);
} else {
    echo "Aucun thème sélectionné.";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Articles - <?php echo htmlspecialchars($theme['titre']); ?></title>

    <!-- Liens CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">

    <!-- Styles personnalisés -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }

        .section-bg {
            background: linear-gradient(to right, #b78752); /* Couleur café */
            color: #fff;
            padding: 40px 0;
        }

        .article-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .article-card img {
            width: 100%; /* Largeur complète de la carte */
            height: 200px; /* Hauteur fixe */
            object-fit: cover; /* Ajuste l'image en rognant si nécessaire */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .card-text {
            font-size: 0.95rem;
            color: #666;
        }

        .feedback-form {
            margin-top: 20px;
        }

        .feedback-input {
            width: 100%;
            height: 80px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .submit-btn {
            background-color: #6F4E37; /* Couleur café */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #5b3d2e; /* Couleur café plus foncée */
        }

        .empty-message {
            font-size: 1.25rem;
            color: #fff;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main>
        <section class="section-bg">
            <div class="container">
                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-8">
                        <h2>Articles du thème : <?php echo htmlspecialchars($theme['titre']); ?></h2>
                    </div>
                </div>
                <div class="row">
                    <?php if (count($articles) > 0): ?>
                        <?php foreach ($articles as $article): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="article-card">
                                    <img src="../../../frontOfficeBib/<?php echo htmlspecialchars($article['Image_article']); ?>" alt="Image de l'article">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($article['Titre_article']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($article['Description_article']); ?></p>

                                        <!-- Formulaire de feedback -->
                                        <div class="feedback-form">
                                            <label for="feedback-<?php echo $article['id']; ?>">Votre feedback :</label>
                                            <textarea id="feedback-<?php echo $article['id']; ?>" class="feedback-input" placeholder="Écrivez votre retour..."></textarea>
                                            <button class="submit-btn" type="button" onclick="submitFeedback(<?php echo $article['id']; ?>)">Envoyer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-message">Aucun article disponible pour ce thème.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fonction pour envoyer le feedback (simulation)
        function submitFeedback(articleId) {
            const feedback = document.getElementById(`feedback-${articleId}`).value;

            if (feedback.trim() === "") {
                alert("Veuillez écrire un feedback.");
                return;
            }

            // Pour l'instant, on peut juste afficher un message de confirmation
            alert(`Feedback pour l'article ID ${articleId}: "${feedback}"`);

            // Réinitialisation du champ de feedback
            document.getElementById(`feedback-${articleId}`).value = "";
        }
    </script>
</body>
</html>
