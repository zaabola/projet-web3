<?php
include 'C:/xampp/htdocs/web/controller/PortfolioC.php';
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
$PortfolioC= new PortfolioC();
if(isset($_GET['addPortfolio']))
{
    $p = new Portfolio(
        $_POST['nom'],       // Nom
        $_POST['prenom'],    // Prénom
        $_POST['photo'], // Photo (assumes the file name is used for storage)
        $_POST['langue'],    // Langue
        $_POST['specialite'],// Spécialité
        $_POST['biographie'] // Biographie
    );
    $p->setId_volontaire($_GET['addPortfolio']);
    $PortfolioC->create($p);
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Barista Cafe HTML CSS Template</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
            
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/vegas.min.css" rel="stylesheet">

        <link href="css/tooplate-barista.css" rel="stylesheet">
   
    </head>
    
    <body>
                
            <main>
          

                <section class="contact-section section-padding" id="section_5">
                    <div class="container">
                        <div class="row">   

                            <div class="col-lg-12 col-12">
                                <em class="text-white">empreinte</em>
                                <h2 class="text-white mb-4 pb-lg-2">Creez votre portfolio</h2>
                            </div>

                            <div class="col-lg-6 col-12">
                            <form action="?addPortfolio=<?=$_GET['id']?>" method="post" id="portfolioForm" class="custom-form contact-form">
    <div class="row">
        <div class="col-lg-6 col-12">
            <label for="nom" class="form-label">Nom <sup class="text-danger">*</sup></label>
            <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: Ferchichi">
            <span id="nomError" class="text-danger"></span>
        </div>

        <div class="col-lg-6 col-12">
            <label for="prenom" class="form-label">Prenom <sup class="text-danger">*</sup></label>
            <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Ex: Mariem">
            <span id="prenomError" class="text-danger"></span>
        </div>

        <div class="col-12">
            <label for="photo" class="form-label">Photo <sup class="text-danger">*</sup></label>
            <input type="file" name="photo" id="photo" class="form-control">
            <span id="photoError" class="text-danger"></span>
        </div>

        <div class="col-lg-6 col-12">
            <label for="langue" class="form-label">Langue <sup class="text-danger">*</sup></label>
            <input type="text" name="langue" id="langue" class="form-control" placeholder="Ex: Français, Anglais">
            <span id="langueError" class="text-danger"></span>
        </div>

        <div class="col-lg-6 col-12">
            <label for="specialite" class="form-label">Spécialité <sup class="text-danger">*</sup></label>
            <input type="text" name="specialite" id="specialite" class="form-control" placeholder="Ex: Historien, Guide">
            <span id="specialiteError" class="text-danger"></span>
        </div>

        <div class="col-12">
            <label for="biographie" class="form-label">Biographie <sup class="text-danger">*</sup></label>
            <textarea name="biographie" id="biographie" rows="4" class="form-control" placeholder="Écrivez une courte biographie..."></textarea>
            <span id="biographieError" class="text-danger"></span>
        </div>
    </div>

    <div class="col-lg-5 col-12 mx-auto mt-3">
        <button type="submit" class="form-control">Ajouter Portfolio</button>
    </div>
</form>

<script>
    document.getElementById('portfolioForm').addEventListener('submit', function(e) {
        let isValid = true;

        // Regex for text fields
        const textRegex = /^[a-zA-ZÀ-ÿ\s-]+$/; // Adjusted to include accented characters

        // Get all input values
        let nom = document.getElementById('nom');
        let prenom = document.getElementById('prenom');
        let photo = document.getElementById('photo');
        let langue = document.getElementById('langue');
        let specialite = document.getElementById('specialite');
        let biographie = document.getElementById('biographie');

        // Clear error messages
        document.querySelectorAll('span').forEach(span => span.innerHTML = "");

        // Validate nom
        if (nom.value.trim() === '') {
            document.getElementById('nomError').innerHTML = "Le champ nom est requis.";
            isValid = false;
        } else if (!textRegex.test(nom.value)) {
            document.getElementById('nomError').innerHTML = "Le nom doit contenir uniquement des lettres et des tirets.";
            isValid = false;
        }

        // Validate prenom
        if (prenom.value.trim() === '') {
            document.getElementById('prenomError').innerHTML = "Le champ prénom est requis.";
            isValid = false;
        } else if (!textRegex.test(prenom.value)) {
            document.getElementById('prenomError').innerHTML = "Le prénom doit contenir uniquement des lettres et des tirets.";
            isValid = false;
        }

        // Validate photo
        if (photo.files.length === 0) {
            document.getElementById('photoError').innerHTML = "Veuillez ajouter une photo.";
            isValid = false;
        }

        // Validate langue
        if (langue.value.trim() === '') {
            document.getElementById('langueError').innerHTML = "Le champ langue est requis.";
            isValid = false;
        }

        // Validate specialite
        if (specialite.value.trim() === '') {
            document.getElementById('specialiteError').innerHTML = "Le champ spécialité est requis.";
            isValid = false;
        }

        // Validate biographie
        if (biographie.value.trim() === '') {
            document.getElementById('biographieError').innerHTML = "Le champ biographie est requis.";
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) e.preventDefault();
    });
</script>

                            </div>

                            <div class="col-lg-6 col-12 mx-auto mt-5 mt-lg-0 ps-lg-5">
                              
                            </div>

                        </div>
                    </div>
                </section>

                
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