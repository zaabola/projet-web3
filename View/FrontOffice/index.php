<?php
session_start();
require_once('session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id'])) {
    // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
    header("Location: ../FrontOffice/login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>بصمة</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
            
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/vegas.min.css" rel="stylesheet">

        <link href="css/tooplate-barista.css" rel="stylesheet">
        
        <link rel="icon" href="logo.png">
    </head>
    
    <body>
                
            <main>
                <nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index1.php">
                            <img src="images/logo.png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                            بصمة
                        </a>
        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link " href="index1.php">Home</a>
                                </li>
        
                                <li class="nav-item">
                                    <a class="nav-link" href="#section_1">About</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="#section_2">Excursion</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="#section_3">Reviews</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="#section_4">Contact</a>
                                </li>
                            </ul>

                            <div class="ms-lg-3">
                                <a class="btn custom-btn custom-border-btn" href="reservation.php">
                                    Reservation
                                    <i class="bi-arrow-up-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>


                <section class="hero-section d-flex justify-content-center align-items-center">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 col-12 mx-auto">
                                <em class="small-text">Bienvenue A</em>
                                
                                <h1>بصمة</h1>

                                <p class="text-white mb-4 pb-lg-2">
                                    votre site <em>favorie</em> 
                                </p>

                                <a class="btn custom-btn custom-border-btn smoothscroll me-3" href="#section_1">
                                    A propos
                                </a>

                                <a class="btn custom-btn smoothscroll me-2 mb-2" href="#section_3"><strong>Excursion</strong></a>
                            </div>

                        </div>
                    </div>

                    <div class="hero-slides"></div>
                </section>


                <section class="about-section section-padding" id="section_1">
                    <div class="section-overlay"></div>
                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 col-12">
                                <div class="ratio ratio-1x1">
                                    <video autoplay="" loop="" muted="" class="custom-video" poster="">
                                        <source src="videos/abou-video.mp4" type="video/mp4">

                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>

                            <div class="col-lg-5 col-12 mt-4 mt-lg-0 mx-auto">

                                <p class="text-white">Bienvenue sur بصمة, votre partenaire idéal pour découvrir les trésors cachés de la Tunisie !</p>

                                <p class="text-white">Nous sommes une agence spécialisée dans l'organisation d'excursions et d'activités touristiques à travers tout le pays. Que vous soyez passionné d’histoire, amoureux de la nature ou en quête d'aventure, nous vous proposons des expériences uniques pour explorer la richesse et la diversité de la Tunisie</p>
                                <h2 class="text-white">Notre mission</h2>
                                <p class="text-white">Notre mission est simple : vous offrir des voyages mémorables et authentiques. Nous croyons que chaque excursion est une occasion de créer des souvenirs uniques tout en vous plongeant au cœur des traditions et des paysages tunisiens</p>
                            </div>

                        </div>
                    </div>
                </section>

                <!--debut-->
                <section class="menu-section section-padding" id="section_2">
                    <div class="container">
                        <div class="row">

                            <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                                <div class="menu-block-wrap">
                                    <div class="text-center mb-4 pb-lg-2">
                                        <h4 class="text-white">Decouvrir nos excursions</h4>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>Tozeur</h6>
                                            <span class="underline"></span>

                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Aventure dans le désert, découverte de la culture berbère</small>
                                        </div>
                                    </div>

                                    <div class="menu-block my-4">
                                        <div class="d-flex">
                                            <h6>
                                                Djerba
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Vivre une expérience inoubliable sur l'île de Djerba</small>
                                        </div>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>
                                                El Jem
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Decouvrir l'antique ville romaine d'El Jem</small>
                                        </div>
                                    </div>

                                    <div class="menu-block my-4">
                                        <div class="d-flex">
                                            <h6>
                                                Sidi bou said
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Visite inoubliable de la ville de Sidi Bou Said</small>
                                        </div>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>
                                                Carthage
                                            </h6>
                                        
                                            <span class="underline"></span>

                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Decouvrir l'antique ville de Carthage</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <div class="menu-block-wrap">
                                    <div class="text-center mb-4 pb-lg-2">
                                        <h4 class="text-white">Decouvrir nos excursions</h4>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>Tunis
                                            </h6>
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Visite de la capitale Tunis</small>
                                        </div>
                                    </div>

                                    <div class="menu-block my-4">
                                        <div class="d-flex">
                                            <h6>
                                                Douz
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>oasis, désert et culture traditionnelle </small>
                                        </div>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>
                                                Dougga
                                            </h6>
                                        
                                            <span class="underline"></span>

                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Une ville antique, un patrimoine riche</small>
                                        </div>
                                    </div>

                                    <div class="menu-block my-4">
                                        <div class="d-flex">
                                            <h6>
                                                Kairouan
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Le capitale religieuse de la Tunisie</small>
                                        </div>
                                    </div>

                                    <div class="menu-block">
                                        <div class="d-flex">
                                            <h6>
                                                Ain drahem et Tbarka
                                            </h6>
                                        
                                            <span class="underline"></span>
                                        </div>

                                        <div class="border-top mt-2 pt-2">
                                            <small>Des paysages naturels et des plages de sable fin</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="button-container">
                            <a href="traif.php"><button class="reserve-button">Plus d'informations</button></a>
                        </div>
                    </div>
                </section>

                <section class="reviews-section section-padding section-bg" id="section_3">
                    <div class="container">
                        <div class="row justify-content-center">

                            <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                                <em class="text-white">Reviews by Customers</em>

                                <h2 class="text-white">Top Reviewed : Djerba</h2>
                            </div>

                            <div class="timeline">
                                <div class="timeline-container timeline-container-left">
                                    <div class="timeline-content">
                                        <div class="reviews-block">
                                            <div class="reviews-block-image-wrap d-flex align-items-center">
                                                <img src="images/reviews/young-woman-with-round-glasses-yellow-sweater.jpg" class="reviews-block-image img-fluid" alt="">

                                                <div class="">
                                                    <h6 class="text-white mb-0">Sandra</h6>
                                                    <em class="text-white"> Customers</em>
                                                </div>
                                            </div>

                                            <div class="reviews-block-info">
                                                <p>J’ai passé une journée extraordinaire lors de mon excursion à Djerba ! La visite du village de Guellala avec ses ateliers de poterie a été fascinante, et le musée du patrimoine nous a vraiment plongés dans la culture locale.</p>

                                                <div class="d-flex border-top pt-3 mt-4">
                                                    <strong class="text-white">4.5 <small class="ms-2">Rating</small></strong>

                                                    <div class="reviews-group ms-auto">
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-container timeline-container-right">
                                    <div class="timeline-content">
                                        <div class="reviews-block">
                                            <div class="reviews-block-image-wrap d-flex align-items-center">
                                                <img src="images/reviews/senior-man-white-sweater-eyeglasses.jpg" class="reviews-block-image img-fluid" alt="">

                                                <div class="">
                                                    <h6 class="text-white mb-0">Don</h6>
                                                    <em class="text-white"> Customers</em>
                                                </div>
                                            </div>

                                            <div class="reviews-block-info">
                                                <p>Cette excursion a été une belle manière de découvrir Djerba en une journée. La visite de la synagogue de la Ghriba était un moment fort, plein d’histoire et de spiritualité.Le guide était super et très à l’écoute, ce qui a rendu l’expérience encore plus enrichissante.</p>

                                                <div class="d-flex border-top pt-3 mt-4">
                                                    <strong class="text-white">4.5 <small class="ms-2">Rating</small></strong>

                                                    <div class="reviews-group ms-auto">
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-container timeline-container-left">
                                    <div class="timeline-content">
                                        <div class="reviews-block">
                                            <div class="reviews-block-image-wrap d-flex align-items-center">
                                                <img src="images/reviews/young-beautiful-woman-pink-warm-sweater-natural-look-smiling-portrait-isolated-long-hair.jpg" class="reviews-block-image img-fluid" alt="">

                                                <div class="">
                                                    <h6 class="text-white mb-0">Olivia</h6>
                                                    <em class="text-white"> Customers</em>
                                                </div>
                                            </div>

                                            <div class="reviews-block-info">
                                                <p>L’excursion à Djerba a été un véritable enchantement. Nous avons commencé par le village de Erriadh et ses magnifiques œuvres de Djerbahood, un musée à ciel ouvert qui allie art et tradition. Ensuite, nous avons visité des villages de pêcheurs et découvert le charme des maisons blanches et bleues typiques de l’île.Je repartirai sans hésiter pour une autre aventure avec eux !</p>

                                                <div class="d-flex border-top pt-3 mt-4">
                                                    <strong class="text-white">4.5 <small class="ms-2">Rating</small></strong>

                                                    <div class="reviews-group ms-auto">
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star-fill"></i>
                                                        <i class="bi-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="contact-section section-padding" id="section_4">
                    <div class="container">
                        <div class="row">   

                            <div class="col-lg-12 col-12">
                                <h2 class="text-white mb-4 pb-lg-2">Contact</h2>
                            </div>

                            <div class="col-lg-6 col-12">
                                <form action="#" method="post" class="custom-form contact-form" role="form">

                                    <div class="row">
                                        
                                        <div class="col-lg-6 col-12">
                                            <label for="name" class="form-label">Nom <sup class="text-danger">*</sup></label>

                                            <input type="text" name="name" id="name" class="form-control" placeholder="Jackson">
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <label for="email" class="form-label">Email</label>

                                            <input type="text" name="email" id="email"  class="form-control" placeholder="Jack@gmail.com">
                                        </div>

                                        <div class="col-12">
                                            <label for="message" class="form-label">Comment s'est passé ton excursion</label>

                                            <textarea name="message" rows="4" class="form-control" id="message" placeholder="Message"></textarea>
                                            
                                        </div>
                                    </div>

                                    <div class="col-lg-5 col-12 mx-auto mt-3">
                                        <button type="submit" class="form-control">Envoyer votre avis</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </section>


                <footer class="site-footer">
                    <div class="container">
                        <div class="row">

                            <div class="col-lg-4 col-12 me-auto">
                                <em class="text-white d-block mb-4">Où nous trouver ?</em>

                                <strong class="text-white">
                                    <i class="bi-geo-alt me-2"></i>
                                     Av. Hedi Nouira Ariana, 2001
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
                                    <strong class="me-2">Tel:</strong>
                                    <a href="tel: 305-240-9671" class="site-footer-link">
                                        (216) 
                                        95 020 030
                                    </a>
                                </p>

                                <p class="d-flex">
                                    <strong class="me-2">Email:</strong>

                                    <a href="mailto:info@yourgmail.com" class="site-footer-link">
                                        Basma.Travel@gmail.com
                                    </a>
                                </p>
                            </div>


                            <div class="col-lg-5 col-12">
                                <em class="text-white d-block mb-4">Horaire de travail.</em>

                                <ul class="opening-hours-list">
                                    <li class="d-flex">
                                        Lundi - Vendredi
                                        <span class="underline"></span>

                                        <strong>9:00 - 18:00</strong>
                                    </li>

                                    <li class="d-flex">
                                        Samedi
                                        <span class="underline"></span>

                                        <strong>9:00 - 13:00</strong>
                                    </li>

                                    <li class="d-flex">
                                        Dimanche
                                        <span class="underline"></span>

                                        <strong>Ferme</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-8 col-12 mt-4">
                                <p class="copyright-text mb-0">Copyright © بصمة </p>
                            </div>
                    </div>
                </footer>
            </main>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/vegas.min.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/ContactFormControl.js"></script>

    </body>
</html>