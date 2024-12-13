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
    <link rel="icon" href="logo (1).png">

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
<nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.html">
                        <img src="logo (1).png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                            Empreinte
                        </a>
        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_1">Home</a>
                                </li>
        
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_2">About</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_3">Bibliothèque</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_4">Reviews</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">Contact</a>
                                </li>
                            </ul>

                            
                        </div>
                    </div>
                </nav>

<section class="hero-section d-flex justify-content-center align-items-center" id="section_3">
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #b78752;">Bibliothèque</h2>
                
                <!-- Modification du formulaire de recherche -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-6">
                        <form action="recherche.php" method="GET" class="mb-4" id="searchForm" onsubmit="return validateSearch()">
                            <div class="input-group">
                                <input type="text" name="q" id="searchInput" class="form-control" placeholder="Rechercher un mot..." >
                                <button type="submit" class="btn btn-custom">Rechercher</button>
                            </div>
                            <small id="searchError" class="text-danger" style="display: none;">Veuillez saisir un terme de recherche</small>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" style="margin: 1200px,1200px,1200px;">
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
</section>
<footer class="site-footer">
                    <div class="container">
                        <div class="row">

                            <div class="col-lg-4 col-12 me-auto">
                                <em class="text-white d-block mb-4">Where to find us?</em>

                                <strong class="text-white">
                                    <i class="bi-geo-alt me-2"></i>
                                    Bandra West, Mumbai, Maharashtra 400050, India
                                </strong>

                                <ul class="social-icon mt-4">
                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-facebook">
                                        </a>
                                    </li>
        
                                    <li class="social-icon-item">
                                        <a href="https://x.com/minthu" target="_new" class="social-icon-link bi-twitter">
                                        </a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-whatsapp">
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-3 col-12 mt-4 mb-3 mt-lg-0 mb-lg-0">
                                <em class="text-white d-block mb-4">Contact</em>

                                <p class="d-flex mb-1">
                                    <strong class="me-2">Phone:</strong>
                                    <a href="tel: 305-240-9671" class="site-footer-link">
                                        (65) 
                                        305 2409 671
                                    </a>
                                </p>

                                <p class="d-flex">
                                    <strong class="me-2">Email:</strong>

                                    <a href="mailto:info@yourgmail.com" class="site-footer-link">
                                        hello@barista.co
                                    </a>
                                </p>
                            </div>


                            <div class="col-lg-5 col-12">
                                <em class="text-white d-block mb-4">Opening Hours.</em>

                                <ul class="opening-hours-list">
                                    <li class="d-flex">
                                        Monday - Friday
                                        <span class="underline"></span>

                                        <strong>9:00 - 18:00</strong>
                                    </li>

                                    <li class="d-flex">
                                        Saturday
                                        <span class="underline"></span>

                                        <strong>11:00 - 16:30</strong>
                                    </li>

                                    <li class="d-flex">
                                        Sunday
                                        <span class="underline"></span>

                                        <strong>Closed</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-8 col-12 mt-4">
                                <p class="copyright-text mb-0">Copyright © Barista Cafe 2048 
                                    - Design: <a rel="sponsored" href="https://www.tooplate.com" target="_blank">Tooplate</a></p>
                            </div>
                    </div>
                </footer>

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

<!-- Ajout du script JavaScript avant la fermeture du body -->
<script>
function validateSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchError = document.getElementById('searchError');
    
    // Nettoyer les espaces au début et à la fin
    const searchValue = searchInput.value.trim();
    
    // Vérifier si le champ est vide
    if (searchValue === '') {
        searchError.style.display = 'block';
        searchInput.classList.add('is-invalid');
        return false;
    }
    
    // Si la validation passe
    searchError.style.display = 'none';
    searchInput.classList.remove('is-invalid');
    return true;
}

// Ajouter un écouteur d'événement pour masquer le message d'erreur lors de la saisie
document.getElementById('searchInput').addEventListener('input', function() {
    if (this.value.trim() !== '') {
        document.getElementById('searchError').style.display = 'none';
        this.classList.remove('is-invalid');
    }
});
</script>
</body>
</html>
