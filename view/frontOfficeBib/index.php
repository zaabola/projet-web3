<?php
include_once '../../controller/theme.php';
$themeController = new ThemeController();
$list = $themeController->listtheme();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bibliothèque</title>

    <!-- CSS FILES -->                
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">

    <!-- Styles personnalisés -->
    <style>
        /* Bouton transparent */
        .btn-custom {
            background-color: transparent; /* Fond transparent */
            color: white;                 /* Texte blanc */
            border: 2px solid white;      /* Bordure blanche */
            border-radius: 5px;           /* Bordures légèrement arrondies */
        }

        /* Effet au survol */
        .btn-custom:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Légère transparence au survol */
            color: white;                                /* Texte reste blanc */
            border-color: white;                         /* Bordure blanche */
        }

        /* Focus pour accessibilité */
        .btn-custom:focus {
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5); /* Ombre blanche */
        }
    </style>
</head>
<body>
<main>
    <section class="barista-section section-padding section-bg d-flex align-items-center justify-content-center" id="biblio">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-12 col-12 mb-4 pb-lg-2">
                    <h2 class="text-white">Bibliothèque</h2>
                </div>
            </div>
            <!-- Row pour centrer les thèmes -->
            <div class="row d-flex justify-content-center">
                <?php foreach ($list as $theme): ?>
                    <!-- Div pour chaque thème -->
                    <div class="col-lg-3 col-md-6 col-sm-8 mb-4">
                        <div class="team-block-wrap">
                            <div class="team-block-info d-flex flex-column">
                                <div class="d-flex justify-content-center align-items-center mt-auto mb-3">
                                    <h4 class="text-white mb-0"><?php echo $theme['titre']; ?></h4>
                                </div>
                                <p class="text-white mb-0"><?php echo $theme['description']; ?></p>
                                <!-- Bouton pour consulter les articles -->
                                <div class="text-center mt-3">
                                    <a href="affichage.php?theme_id=<?php echo $theme['id']; ?>" class="btn btn-custom">
                                        Consulter les articles de <?php echo $theme['titre']; ?>
                                    </a>
                                </div>
                            </div>
                            <!-- Conteneur pour l'image -->
                            <div class="team-block-image-wrap">
                                <img src="<?php echo $theme['image']; ?>" class="team-block-image" alt="">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

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
