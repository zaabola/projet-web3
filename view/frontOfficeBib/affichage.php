<?php
// Inclure les fichiers nécessaires
include_once(__DIR__ . '../../../controller/Articlee.php');
include_once(__DIR__ . '../../../controller/theme.php');

// Instancier les contrôleurs
$articlesController = new ArticlesController();
$themeController = new ThemeController();

// Connexion à la base de données pour les feedbacks
$host = 'localhost';
$dbname = 'Emprunt';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Traitement du formulaire de feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id']) && isset($_POST['commentaire'])) {
    try {
        $article_id = $_POST['article_id'];
        $commentaire = trim($_POST['commentaire']); // Nettoyer les espaces

        // Validation du commentaire
        if (empty($commentaire)) {
            throw new Exception("Le commentaire ne peut pas être vide.");
        }

        // Vérification de la longueur
        if (strlen($commentaire) > 500) {
            throw new Exception("Le commentaire ne doit pas dépasser 500 caractères.");
        }

        // Nettoyer et valider le commentaire
        $commentaire = htmlspecialchars($commentaire); // Convertit les caractères spéciaux
        $commentaire = strip_tags($commentaire); // Supprime les balises HTML
        
        // Vérification plus permissive des caractères autorisés
        if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\.,!?'\-\"]+$/u", $commentaire)) {
            throw new Exception("Le commentaire contient des caractères non autorisés. Utilisez uniquement des lettres, chiffres et la ponctuation basique.");
        }

        $query = "INSERT INTO feed_back (Id_article, commentaire) VALUES (:article_id, :commentaire)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();

        // Redirection pour éviter la soumission multiple du formulaire
        header("Location: " . $_SERVER['PHP_SELF'] . "?theme_id=" . $_GET['theme_id']);
        exit();
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Vérifier si 'theme_id' est présent dans l'URL
if (isset($_GET['theme_id'])) {
    $theme_id = $_GET['theme_id'];
    $theme = $themeController->getThemeById($theme_id);
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
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">

    <!-- Intégration du fichier CSS -->
    <style>
        :root {
          --white-color:                  #e6dede;
          --primary-color:                #BC6C25;
          --secondary-color:              #DDA15E;
          --section-bg-color:             #b78752;
          --custom-btn-bg-color:          #BC6C25;
          --custom-btn-bg-hover-color:    #DDA15E;
          --dark-color:                   #000000;
          --p-color:                      #717275;
          --border-color:                 #7fffd4;
          --link-hover-color:             #E76F51;
        
          --body-font-family:             'Plus Jakarta Sans', sans-serif;
        
          --h1-font-size:                 68px;
          --h2-font-size:                 46px;
          --h3-font-size:                 32px;
          --h4-font-size:                 28px;
          --h5-font-size:                 24px;
          --h6-font-size:                 22px;
          --p-font-size:                  20px;
          --btn-font-size:                16px;
          --form-btn-font-size:           18px;
          --menu-font-size:               16px;
        
          --border-radius-large:          100px;
          --border-radius-medium:         20px;
          --border-radius-small:          10px;
        
          --font-weight-thin:             200;
          --font-weight-light:            300;
          --font-weight-normal:           400;
          --font-weight-bold:             700;
        }

        body {
          background-color: var(--white-color);
          font-family: var(--body-font-family); 
        }

        .section-bg {
            background: var(--section-bg-color);
            color: var(--white-color);
            padding: 40px 0;
        }

        .article-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border-radius: var(--border-radius-small);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: var(--primary-color); /* Changer la couleur des cartes */
            transition: transform 0.2s;
        }

        .article-card:hover {
            transform: scale(1.05);
        }

        .article-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: var(--border-radius-small);
            border-top-right-radius: var(--border-radius-small);
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: var(--h5-font-size);
            font-weight: var(--font-weight-bold);
            color: var(--white-color); /* Couleur du texte des titres */
        }

        .card-text {
            font-size: var(--p-font-size);
            color: var(--white-color); /* Couleur du texte des descriptions */
            margin-bottom: 10px;
        }

        .bibliography {
            font-size: var(--btn-font-size);
            color: var(--white-color); /* Couleur du texte des bibliographies */
            margin-top: 10px;
        }

        .feedback-form {
            margin-top: 20px;
        }

        .feedback-input {
            width: 100%;
            height: 80px;
            padding: 10px;
            border-radius: var(--border-radius-small);
            border: 1px solid var(--border-color);
            margin-bottom: 10px;
            font-size: var(--btn-font-size);
        }

        .submit-btn {
            background-color: var(--secondary-color); /* Couleur plus claire pour le bouton */
            color: var(--dark-color); /* Texte sombre pour contraste */
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius-small);
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: var(--link-hover-color); /* Couleur de survol pour le bouton */
        }

        .empty-message {
            font-size: var(--h4-font-size);
            color: var(--white-color);
            text-align: center;
            margin-top: 20px;
        }

        /* Ajout du style pour la popup d'alerte */
        .alert-popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .alert-popup.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-popup.show {
            display: block;
            animation: fadeOut 5s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .button-group .submit-btn {
            flex: 1;
        }

        .button-group a.submit-btn {
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Ajouter la div pour l'alerte au début du body -->
    <div id="alertPopup" class="alert-popup"></div>

    <main>
        <section class="section-bg">
            <div class="container">
                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-8">
                        <h2>Articles du thème : <?php echo htmlspecialchars($theme['titre']); ?></h2>
                    </div>
                </div>
                <div class="row">
                    <?php if (empty($articles)): ?>
                        <div class="col-12 text-center">
                            <p class="empty-message">Aucun article actif n'est disponible pour ce thème.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($articles as $article): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="article-card">
                                    <img src="../../../frontOfficeBib/<?php echo htmlspecialchars($article['Image_article']); ?>" alt="Image de l'article">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($article['Titre_article']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($article['Description_article']); ?></p>
                                        
                                        <!-- Bibliographie -->
                                        <p class="bibliography">Bibliographie : <?php echo htmlspecialchars($article['bibliographie']); ?></p>

                                        <!-- Formulaire de feedback -->
                                        <div class="feedback-form">
                                            <form method="POST" action="" onsubmit="return validateFeedback(this, <?php echo $article['Id_article']; ?>)">
                                                <input type="hidden" name="article_id" value="<?php echo $article['Id_article']; ?>">
                                                <label for="feedback-<?php echo $article['Id_article']; ?>">Votre feedback :</label>
                                                <textarea id="feedback-<?php echo $article['Id_article']; ?>" 
                                                          name="commentaire" 
                                                          class="feedback-input" 
                                                          placeholder="Écrivez votre retour..."></textarea>
                                                <div class="button-group">
                                                    <button class="submit-btn" type="submit">Envoyer</button>
                                                    <a href="generate_pdf.php?Id_article=<?php echo htmlspecialchars($article['Id_article']); ?>" 
                                                       class="submit-btn" 
                                                       target="_blank">
                                                        <i class="bi bi-download"></i> Télécharger PDF
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function validateFeedback(form, articleId) {
            const commentaire = form.querySelector(`#feedback-${articleId}`).value.trim();
            
            // Vérification si le commentaire est vide
            if (commentaire === '') {
                alert('Le commentaire ne peut pas être vide.');
                return false;
            }

            // Vérification de la longueur
            if (commentaire.length > 500) {
                alert('Le commentaire ne doit pas dépasser 500 caractères.');
                return false;
            }

            // Vérification des caractères autorisés
            const regexCaracteresAutorises = /^[a-zA-ZÀ-ÿ0-9\s\.,!?]+$/;
            if (!regexCaracteresAutorises.test(commentaire)) {
                alert('Caractères non autorisés. Utilisez uniquement des lettres, chiffres, espaces et ponctuation simple (.,!?)');
                return false;
            }

            // Vérification des bad words
            if (containsBadWords(commentaire)) {
                alert('Votre commentaire contient des mots inappropriés.');
                return false;
            }

            // Si toutes les validations sont passées, afficher le message de succès
            alert('Votre feedback a été ajouté avec succès !');
            return true;
        }

        // Reste du code pour les bad words
        const badWords = [
            'merde', 'putain', 'connard', 'connasse', 'salope', 'pute', 'enculé',
            'bite', 'couille', 'fuck', 'shit', 'ass', 'bitch', 'damn'
        ];

        function containsBadWords(text) {
            const lowerText = text.toLowerCase();
            return badWords.some(word => lowerText.includes(word));
        }
    </script>
</body>
</html>