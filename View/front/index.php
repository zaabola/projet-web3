
<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Empreinte Tunisie</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
            
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/vegas.min.css" rel="stylesheet">

        <link href="css/tooplate-barista.css" rel="stylesheet">
        
<!--

Tooplate 2137 Barista Cafe

https://www.tooplate.com/view/2137-barista-cafe

Bootstrap 5 HTML CSS Template

-->
    </head>
    
    <body>
                
            <main>
                <nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.php">
                            <img src="logo.png" class="navbar-brand-image img-fluid" alt="logo basma">
                            Basma
                        </a>
        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#">Home</a>
                                </li>
        
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#">About</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#">Shop</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#">Reviews</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#">Contact</a>
                                </li>
                            </ul>

                            <div class="ms-lg-3">
                                
                                <?php if (!$isLoggedIn): ?>
                                <li><a class="btn custom-btn custom-border-btn" href="signin.php">Se connecter <i class="bi-arrow-up-right ms-2"></i></a></li>
                                <?php else: ?>
                                <li><a class="btn custom-btn custom-border-btn" href="logout.php" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">Se déconnecter <i class="bi-arrow-up-right ms-2"></i></a></li>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </nav>


                <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 col-12 mx-auto">
                                <em class="small-text">welcome to Empreinte Tunisie</em>
                                
                                <h1>Basma</h1>

                                <p class="text-white mb-4 pb-lg-2">
                                    your <em>favourite</em> guide to Tunisia.
                                </p>

                                <a class="btn custom-btn custom-border-btn smoothscroll me-3" href="#">
                                    Our Story
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="hero-slides"></div>
                </section>


                >


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