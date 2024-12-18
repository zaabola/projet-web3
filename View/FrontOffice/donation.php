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
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";
//require '../config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['booking-form-name'] ?? '');
    $email = htmlspecialchars($_POST['booking-form-email'] ?? '');
    $amount = htmlspecialchars($_POST['booking-form-number'] ?? '');
    $message = htmlspecialchars($_POST['booking-form-message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($amount)) {
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Veuillez entrer un email valide.";
        } elseif (!is_numeric($amount) || $amount <= 0) {
            $error = "Le montant doit être un nombre positif.";
        } else {

            try {
                $sql = "INSERT INTO donation (donor_name, donor_email, donation_amount, message, donation_date) 
                        VALUES (:name, :email, :amount, :message, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':amount' => $amount,
                    ':message' => $message
                ]);
                $success = "Votre donation a été enregistrée avec succès !";
            } catch (PDOException $e) {
                $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
        if  (empty($errors)) {
            // Redirection vers credit.php après un délai de 1 seconde (temps pour l'utilisateur de voir le message)
            header("Location: credit.php");
            exit();
    } 
    } 
    else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
   
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>emprunt - Donation Form</title>
    <!-- CSS FILES -->                
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/tooplate-barista.css" rel="stylesheet">
 <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url('../images/vue_mosqué_tunis-1-scaled.jpg') no-repeat center center fixed; /* Remplacez 'images/image.png' par le chemin de votre image */
    background-size: cover;
    color: #fff; /* Couleur de texte pour garantir la lisibilité */
}

 </style>
    <script>
        // Fonction pour définir le montant de la donation
        function setDonationAmount(amount) {
            document.getElementById('donationAmount').value = amount; // Définit la valeur dans le champ caché
            document.getElementById('customDonationAmount').value = amount; // Affiche dans le champ visible
        }
    </script>
</head>
<body class="donation-page">
<main>
<nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.html">
                            <img src="../images/logo.png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                            بصمة
                        </a>
        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_1">Home</a>
                                </li>
        
                              
                                
                                <li>
                                    <a href="index1.php#section_69" class="nav-link click-scroll">Shop</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">Reclamation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="panier.php">Panier</a>
                                </li>
                            </ul>

                            <button id="lang-switch" class="btn btn-outline-primary me-2" >Switch Language</button>

                        </div>
                    
                

                        <div class="d-flex ms-3">
                         <a href="reservation.php" class="btn btn-outline-primary me-2">Reservation</a>
                         <a href="donation.php" class="btn btn-outline-primary me-2">Donation</a>
                        </div>
                        </div>
        </div>
    </div>
</nav>


    <section >
        <div class="container">
            <div class="form-container">
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="booking-form-wrap">
                        <form class="custom-form booking-form" method="post" action="">
                            <div class="text-center mb-4 pb-lg-2">
                                <em class="text-white">Remplissez le formulaire de donation</em>
                                <h2 class="text-white">Donation</h2>
                                <?php if (!empty($success)): ?>
                                    <p class="text-success"><?= htmlspecialchars($success); ?></p>
                                <?php elseif (!empty($error)): ?>
                                    <p class="text-danger"><?= htmlspecialchars($error); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="booking-form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="booking-form-name" class="form-control" placeholder="Nom complet" >
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="booking-form-email" class="form-control" placeholder="Votre email" >
                                    </div>       
                                    <div class="col-lg-12 col-12 mt-3 text-center">
                                    <label class="text-white"><strong>Montant de la donation:</strong></label><br>
                                        <button type="button" class="form-control" onclick="setDonationAmount(20)">20 €</button>
                                        <button type="button" class="form-control" onclick="setDonationAmount(40)">40 €</button>
                                        <button type="button" class="form-control" onclick="setDonationAmount(60)">60 €</button>
                                    </div>
                                    <!-- Champ caché pour stocker la valeur -->
                                    <input type="hidden" name="booking-form-number" id="donationAmount" value="">
                                    <!-- Champ visible pour la valeur personnalisée -->
                                    <div class="col-lg-12 col-12 mt-3 text-center">
                                    <input type="text" name="booking-form-number" id="customDonationAmount" class="form-control" placeholder="Montant de la donation personnalisé" value="<?= isset($amount) ? htmlspecialchars($amount) : '' ?>">

                                    </div>
                                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                                        <button type="submit" class="form-control">suivant</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
