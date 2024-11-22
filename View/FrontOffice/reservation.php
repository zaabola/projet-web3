<?php
require_once 'C:/xampp/htdocs/reservation/Controller/GestionReservation.php';

$success = $error = "";

// Check if form was submitted and data is valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-reservation']) && isset($_POST['formValid']) && $_POST['formValid'] == 'true') {
    try {
        // Extract form data
        $nom = $_POST['last-name'] ?? '';
        $prenom = $_POST['first-name'] ?? '';
        $mail = $_POST['mail'] ?? '';
        $tel = $_POST['tel'] ?? '';
        $destination = $_POST['destination'] ?? '';
        $commentaire = $_POST['commentaire'] ?? '';
        $date = new DateTime(); // Current date and time

        // Create a reservation object
        $reservation = new reservevation($nom, $prenom, $mail, $tel, $destination, $commentaire, $date);

        // Use GestionReservation to add the reservation
        $gestionReservation = new GestionReservation();
        $gestionReservation->createReservation($reservation);

        $success = "Réservation ajoutée avec succès !";

        // Redirect to avoid resubmitting the form on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Reservation Form for Excursions">
    <meta name="author" content="Your Name">
    <title>Réservation - بصمة</title>

    <!-- CSS Files -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">
</head>

<body class="reservation-page">
<main>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.png" class="navbar-brand-image img-fluid" alt="Logo">
                بصمة
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-lg-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#section_1">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_2">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_3">Excursion</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_4">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#section_5">Contact</a></li>
                </ul>
                <div class="ms-lg-3">
                    <a class="btn custom-btn custom-border-btn" href="index.php">Home<i class="bi-arrow-up-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Reservation Form -->
    <section class="booking-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="booking-form-wrap">
                        <div class="row">
                            <div class="col-lg-7 col-12 p-0">
                                <form class="custom-form booking-form" action="#" method="post" role="form" onsubmit="return verifyInputs()" novalidate>
                                    <input type="hidden" name="formValid" id="formValid" value="false">
                                    <div class="text-center mb-4 pb-lg-2">
                                        <em class="text-white">Remplir le formulaire de réservation</em>
                                        <h2 class="text-white">Réserver une excursion</h2>
                                    </div>
                                    <div class="booking-form-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-12">
                                                <input type="text" name="last-name" id="last-name" class="form-control" placeholder="Nom">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="text" name="first-name" id="first-name" class="form-control" placeholder="Prénom">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="email" name="mail" id="mail" class="form-control" placeholder="Email">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="tel" name="tel" id="tel" class="form-control" placeholder="Téléphone">
                                            </div>
                                            <div class="col-lg-12 col-12">
                                                <select name="destination" id="destination" class="form-control">
                                                    <option value="" disabled selected>Choisir une excursion</option>
                                                    <option value="Tozeur">Tozeur</option>
                                                    <option value="Djerba">Djerba</option>
                                                    <option value="El Jem">El Jem</option>
                                                    <option value="Sidi Bou Said">Sidi Bou Said</option>
                                                    <option value="Carthage">Carthage</option>
                                                    <option value="Tunis">Tunis</option>
                                                    <option value="Dougga">Dougga</option>
                                                    <option value="Kairouan">Kairouan</option>
                                                    <option value="Ain drahem et Tbarka">Ain drahem et Tbarka</option>
                                                    
                                                </select>
                                                <textarea name="commentaire" rows="3" class="form-control" placeholder="Commentaire (optionnel)"></textarea>
                                            </div>
                                            <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                                                <button type="submit" class="form-control" name="add-reservation">Réserver</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-5 col-12 p-0">
                                <img src="images/BusTrip.jpeg" class="booking-form-image img-fluid" alt="Bus Trip">
                            </div>
                        </div>
                    </div>
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

<!-- JavaScript Files -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/ReservationFormControl.js"></script>


</body>
</html>
