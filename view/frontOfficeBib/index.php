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
    die("Erreur de connexion : " . $e->getMessage());
}

// Pagination
$itemsPerPage = 3; // Nombre maximum de thèmes par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Récupération des thèmes avec la pagination
$query = "SELECT * FROM theme LIMIT :offset, :itemsPerPage";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération du nombre total de thèmes pour la pagination
$totalQuery = "SELECT COUNT(*) AS total FROM theme";
$totalStmt = $pdo->query($totalQuery);
$totalThemes = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalThemes / $itemsPerPage);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bibliothèque</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS FILES -->                
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f4ef; /* Couleur de fond claire */
            color: #212529; /* Couleur du texte */
        }
        .btn-custom {
            background-color: #b78752; /* Couleur personnalisée */
            color: #fff; /* Texte blanc */
            border: 2px solid #b78752;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #a36d46; /* Couleur légèrement plus foncée au survol */
            color: #fff;
            border-color: #a36d46;
        }
        .team-block-wrap {
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Aligne le contenu en haut */
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff; /* Fond blanc */
            text-align: center;
            min-height: 400px; /* Hauteur minimale des conteneurs */
            max-height: 400px; /* Hauteur maximale pour forcer le défilement */
            overflow-y: auto; /* Ajout d'une barre de défilement verticale si le contenu dépasse */
        }
        .team-block-image {
            width: 100%;
            height: 200px; /* Taille fixe pour les images */
            object-fit: cover; /* Remplissage proportionnel des images */
            border-radius: 10px;
        }
        nav .page-link {
            color: #b78752;
        }
        nav .page-item.active .page-link {
            background-color: #b78752;
            color: #fff;
            border-color: #b78752;
        }
        /* Personnalisation de la barre de défilement */
        .team-block-wrap::-webkit-scrollbar {
            width: 6px;
        }
        .team-block-wrap::-webkit-scrollbar-thumb {
            background-color: #b78752;
            border-radius: 10px;
        }
        .team-block-wrap::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<main>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #b78752;">Bibliothèque</h2>
                
                <!-- Ajout du formulaire de recherche -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-6">
                        <form action="recherche.php" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Rechercher un mot..." required>
                                <button type="submit" class="btn btn-custom">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if (empty($list)): ?>
                    <div class="col-12">
                        <p class="text-center">Aucun thème disponible.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($list as $theme): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="team-block-wrap">
                                <img src="<?php echo htmlspecialchars($theme['image']); ?>" class="team-block-image" alt="Image du thème">
                                <h4 style="color: #b78752;" class="mt-3"><?php echo htmlspecialchars($theme['titre']); ?></h4>
                                <p><?php echo nl2br(htmlspecialchars($theme['description'])); ?></p>
                                <a href="affichage.php?theme_id=<?php echo $theme['id']; ?>" class="btn btn-custom mt-3">Consulter les articles</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center mt-4">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Précédent">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Suivant">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </section>
</main>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script> 

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/click-scroll.js"></script>
<script src="js/vegas.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
