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
            position: relative;
            width: 300px;
            height: 400px;
            background: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
            margin: 20px auto;
        }

        .article-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
        }

        .article-card:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .card-body {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    width: 100%;
    height: 100%;
    padding: 20px;
    box-sizing: border-box;
    background-color: #fff;
    opacity: 0;
    transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);

    /* Ajout du scrolling vertical */
    overflow-y: auto;
    max-height: 90%; /* Ajustez selon vos besoins */
}

.article-card:hover .card-body {
    transform: translate(-50%, -50%) rotate(0deg);
    opacity: 1;
}


        .card-title {
    font-size: var(--h5-font-size);
    font-weight: var(--font-weight-bold);
    color: #000000; /* Couleur noire */
}

.card-text {
    font-size: var(--p-font-size);
    color: #000000; /* Couleur noire */
    margin-bottom: 10px;
}

.bibliography {
    font-size: var(--btn-font-size);
    color: #000000; /* Couleur noire */
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
            padding: 3px 8px; /* Réduit de 5px 10px à 3px 8px */
        background: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100%);
        border: none;
        border-radius: 4px; /* Réduit de 5px à 4px */
        color: white;
        cursor: pointer;
        font-size: 11px; /* Réduit de 12px à 11px */
        flex: 1;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        white-space: nowrap; 
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
            .card-title {
    font-size: var(--h5-font-size);
    font-weight: var(--font-weight-bold);
    color: #000000; /* Couleur noire */
}

.card-text {
    font-size: var(--p-font-size);
    color: #000000; /* Couleur noire */
    margin-bottom: 10px;
}

.bibliography {
    font-size: var(--btn-font-size);
    color: #000000; /* Couleur noire */
    margin-top: 10px;
}

        }

        .button-group .submit-btn {
            flex: 1;
        }

        .button-group a.submit-btn {
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .feedback-item {
            padding: 10px;
            margin-bottom: 10px;
        }

        .feedback-content {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .modal-content {
            background: #fff;
            border-radius: 10px;
        }

        .modal-header {
            background: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Ajouter ces styles pour le bouton de lecture */
        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            font-size: 11px;
            white-space: nowrap;
        }

        .submit-btn i {
            font-size: 14px;
        }

        .submit-btn.reading {
            background: #ff0f7b;
        }
        /* From Uiverse.io by Cornerstone-04 */ 
.box {
  width: 140px;
  height: auto;
  float: left;
  transition: .5s linear;
  position: relative;
  display: block;
  overflow: hidden;
  padding: 15px;
  text-align: center;
  margin: 0 5px;
  background: transparent;
  text-transform: uppercase;
  font-weight: 900;
}

.box:before {
  position: absolute;
  content: '';
  left: 0;
  bottom: 0;
  height: 4px;
  width: 100%;
  border-bottom: 4px solid transparent;
  border-left: 4px solid transparent;
  box-sizing: border-box;
  transform: translateX(100%);
}

.box:after {
  position: absolute;
  content: '';
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  border-top: 4px solid transparent;
  border-right: 4px solid transparent;
  box-sizing: border-box;
  transform: translateX(-100%);
}

.box:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
}

.box:hover:before {
  border-color: #262626;
  height: 100%;
  transform: translateX(0);
  transition: .3s transform linear, .3s height linear .3s;
}

.box:hover:after {
  border-color: #262626;
  height: 100%;
  transform: translateX(0);
  transition: .3s transform linear, .3s height linear .5s;
}

button {
  color: black;
  text-decoration: none;
  cursor: pointer;
  outline: none;
  border: none;
  background: transparent;
}

        /* Animation pour l'icône de lecture */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .reading i {
            animation: pulse 1s infinite;
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
                                                          <button type="button"  onclick="startVoiceRecognition(<?php echo $article['Id_article']; ?>)">
        🎤 <span class="box">Enregistrer</span>
    </button>
                                                          

                                                          <div class="button-group" style="display: flex; flex-direction: column; gap: 10px;">
    <button  type="submit" data-translate="envoyer">
        <i class="bi bi-send"></i> <span class="box">Envoyer</span>
    </button>
    <button type="button"  onclick="window.open('generate_pdf.php?Id_article=<?php echo htmlspecialchars($article['Id_article']); ?>', '_blank')">
        <i class="bi bi-download"></i> <span class="box">PDF</span>
    </button>
    <button type="button"  onclick="loadFeedbacks(<?php echo $article['Id_article']; ?>)">
        <i class="bi bi-chat-dots"></i> <span class="box">Feedbacks</span>
    </button>
    <button type="button"  onclick="readArticle(this)" 
            data-title="<?php echo htmlspecialchars($article['Titre_article']); ?>"
            data-description="<?php echo htmlspecialchars($article['Description_article']); ?>"
            data-bibliography="<?php echo htmlspecialchars($article['bibliographie']); ?>">
        <i class="bi bi-volume-up"></i> <span class="box">Lire</span>
    </button>
    <button type="button"  onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://votre-site.com/article.php?id=' . $article['Id_article']); ?>', '_blank')">
        <i class="bi bi-facebook"></i> <span class="box">Partager</span>
    </button>
    <button type="button"  onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo urlencode('http://votre-site.com/article.php?id=' . $article['Id_article']); ?>&text=<?php echo urlencode($article['Titre_article']); ?>', '_blank')">
        <i class="bi bi-twitter"></i> <span class="box">Tweeter</span>
    </button>
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
    <script>
    // Fonction de démarrage de la reconnaissance vocale
    function startVoiceRecognition(articleId) {
        // Vérifiez si le navigateur prend en charge la reconnaissance vocale
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            alert("La reconnaissance vocale n'est pas prise en charge dans ce navigateur.");
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = 'fr-FR'; // Définit la langue à français
        recognition.interimResults = false; // Désactive les résultats intermédiaires
        recognition.maxAlternatives = 1; // Limite à une seule alternative

        // Démarre la reconnaissance
        recognition.start();

        recognition.onstart = () => {
            alert('Parlez maintenant, la reconnaissance vocale est activée.');
        };

        recognition.onspeechend = () => {
            recognition.stop();
        };

        recognition.onresult = (event) => {
            const result = event.results[0][0].transcript; // Transcription de l'audio
            const textarea = document.querySelector(`#feedback-${articleId}`);
            textarea.value += result; // Ajoute la transcription au champ de texte
        };

        recognition.onerror = (event) => {
            alert('Erreur lors de la reconnaissance vocale : ' + event.error);
        };
    }
</script>

<script>
function loadFeedbacks(articleId) {
    const modal = new bootstrap.Modal(document.getElementById('feedbacksModal'));
    const feedbacksList = document.getElementById('feedbacksList');
    
    feedbacksList.innerHTML = '<div class="text-center">Chargement...</div>';
    
    fetch(`get_feedbacks.php?article_id=${articleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                feedbacksList.innerHTML = '<p class="text-center">Aucun feedback pour cet article.</p>';
            } else {
                feedbacksList.innerHTML = data.map(feedback => `
                    <div class="feedback-item">
                        <p class="feedback-content">${feedback.commentaire}</p>
                        <hr>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            feedbacksList.innerHTML = '<p class="text-center text-danger">Erreur lors du chargement des feedbacks.</p>';
            console.error('Erreur:', error);
        });
    
    modal.show();
}
</script>

<!-- Modal pour les feedbacks -->
<div class="modal fade" id="feedbacksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedbacks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="feedbacksList">
                <!-- Les feedbacks seront chargés ici -->
            </div>
        </div>
    </div>
</div>

<script>
let isReading = false;
let currentUtterance = null;

function readArticle(button) {
    if (!isReading) {
        // Commencer la lecture
        const synth = window.speechSynthesis;
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');
        const bibliography = button.getAttribute('data-bibliography');
        
        const textToRead = `Titre: ${title}. Description: ${description}. Bibliographie: ${bibliography}.`;
        currentUtterance = new SpeechSynthesisUtterance(textToRead);
        currentUtterance.lang = 'fr-FR';
        currentUtterance.rate = 1.0; // Vitesse normale
        
        // Mettre à jour le bouton
        button.querySelector('.bi').classList.remove('bi-volume-up');
        button.querySelector('.bi').classList.add('bi-pause-fill');
        button.querySelector('.read-text').textContent = 'Pause';
        
        // Événement de fin de lecture
        currentUtterance.onend = function() {
            resetButton(button);
        };
        
        synth.speak(currentUtterance);
        isReading = true;
    } else {
        // Arrêter la lecture
        window.speechSynthesis.cancel();
        resetButton(button);
    }
}

function resetButton(button) {
    button.querySelector('.bi').classList.remove('bi-pause-fill');
    button.querySelector('.bi').classList.add('bi-volume-up');
    button.querySelector('.read-text').textContent = 'Lire';
    isReading = false;
    currentUtterance = null;
}

// Arrêter la lecture quand la modal est fermée
document.addEventListener('hidden.bs.modal', function () {
    if (isReading) {
        window.speechSynthesis.cancel();
        const buttons = document.querySelectorAll('.submit-btn');
        buttons.forEach(resetButton);
    }
});
</script>

</body>
</html>