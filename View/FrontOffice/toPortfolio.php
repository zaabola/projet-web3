<?php
include 'C:/xampp/htdocs/reservation/Controller/PortfolioC.php';

$PortfolioC = new PortfolioC();
$guides = $PortfolioC->findone($_GET['id']);
?> 
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Portfolio Display">
        <meta name="author" content="">

        <title>Single Portfolio Display</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/tooplate-barista.css" rel="stylesheet">
        <link href="css/portfolio.css" rel="stylesheet">
        
    </head>
    
    <body>
        
        <main>
            <div class="container">
                <?php foreach ($guides as $g): ?>
                    <div class="portfolio-card">
                        <img src="images/<?= $g['photo'] ?>" alt="<?= $g['nom'] ?> <?= $g['prenom'] ?>">
                        <h2><?= $g['nom'] ?> <?= $g['prenom'] ?></h2>
                        <p class="specialite">Specialty: <?= $g['specialite'] ?></p>
                        <p class="langue">Language: <?= $g['langue'] ?></p>
                        <p><?= $g['biographie'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/vegas.min.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>
*/

