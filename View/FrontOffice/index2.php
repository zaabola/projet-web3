<?php
include 'C:/xampp/htdocs/reservation/Controller/volntaireC.php';

$volntaireC = new VolontaireC();
$PortfolioC = new PortfolioC();
if(isset($_GET['addVolantaire']))
{
    $Volontaire = new Volontaire($_POST['nom'],$_POST['prenom'],$_POST['numero'],$_POST['message'],$_POST['email']);
    $volntaireC->create($Volontaire);
}

$guides=$PortfolioC->read();
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
                <nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index2.html">
                            <img src="images/coffee-beans.png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                            Barista
                        </a>
        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-lg-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index1.php">Home</a>
                                </li>
        
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_2">guides</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_3">Our Menu</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_4">Volontariat</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">form</a>
                                </li>
                            </ul>

                            <div class="ms-lg-3">
                                <a class="btn custom-btn custom-border-btn" href="reservation.html">
                                    Reservation
                                    <i class="bi-arrow-up-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>


                <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">

                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 col-12 mx-auto">
                                <em class="small-text">welcome to Barista.co</em>
                                
                                <h1>Cafe Klang</h1>

                                <p class="text-white mb-4 pb-lg-2">
                                    your <em>favourite</em> coffee daily lives.
                                </p>

                                <a class="btn custom-btn custom-border-btn smoothscroll me-3" href="#section_2">
                                    Our Story
                                </a>

                                <a class="btn custom-btn smoothscroll me-2 mb-2" href="#section_3"><strong>Check Menu</strong></a>
                            </div>

                        </div>
                    </div>

                    <div class="hero-slides"></div>
                </section>


               


               

               

                <section class="about-section section-padding" id="section_4">
                    <div class="section-overlay"></div>
                    <div class="container">
                        <div class="row align-items-center">

                            <div class="col-lg-6 col-12">
                                <div class="ratio ratio-1x1">
                                    <video autoplay="" loop="" muted="" class="custom-video" poster="">
                                        <source src="video1.mp4" type="video/mp4">

                                        Your browser does not support the video tag.
                                    </video>

                                    <div class="about-video-info d-flex flex-column">
                                        <h4 class="mt-auto"> Rejoignez nous et partagez votre passion pour la Tunisie !</h4>

                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 col-12 mt-4 mt-lg-0 mx-auto">
                                <em class="text-white">Empreinte</em>

                                <h2 class="text-white mb-3">Volontariat</h2>

                                <p class="text-white">Rejoignez-nous pour faire découvrir notre patrimoine culturel et naturel aux visiteurs, tout en contribuant à sa préservation. Inscrivez-vous pour guider des excursions, raconter nos histoires, et faire briller la beauté de la Tunisie !.</p>

                                <p class="text-white">En tant que guide, vous accompagnerez des groupes lors d’excursions dans des lieux uniques, en valorisant notre histoire et nos coutumes. .</p>

                                <a href="#section_5" class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4">apply</a>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="contact-section section-padding" id="section_5">
                    <div class="container">
                        <div class="row">   

                            <div class="col-lg-12 col-12">
                                <em class="text-white">empreinte</em>
                                <h2 class="text-white mb-4 pb-lg-2">Formulaire Pour guide</h2>
                            </div>

                            <div class="col-lg-6 col-12">
                                <form action="?addVolantaire" method="post" id="formr" class="custom-form contact-form" role="form">
                                <div class="row">
                                    
                                    <div class="col-lg-6 col-12">
                                        <label for="nom" class="form-label">Nom <sup class="text-danger">*</sup></label>
                                        <input type="text" name="nom" id="nom" class="form-control" placeholder=" ferchichi" >
                                        <span id="nomr"></span>
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <label for="Prenom" class="form-label">Prenom</label>
                                        <input type="text" name="prenom" id="prenom"  class="form-control" placeholder="mariem" >
                                        <span id="prenomr"></span>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <label for="message" class="form-label">experience&motivation</label>
                                        <textarea name="message" rows="4" class="form-control" id="message" placeholder="Message" ></textarea>
                                        <span id="expr"></span>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="numero" class="form-label">Numero tel  <sup class="text-danger">*</sup></label>
                                        <input type="text" name="numero" id="numero" class="form-control" placeholder="+216*******" >
                                        <span id="numeror"></span>
                                    </div>
                                    <hr>
                                    <div class="col-lg-6 col-12">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Mariem@gmail.com" >
                                        <span id="emailr"></span>
                                    </div>

                                </div>

                                <div class="col-lg-5 col-12 mx-auto mt-3">
                                    <button type="submit" class="form-control">Send Form</button>
                                </div>
                               
                            </form>
                            <script>
                                let myform = document.getElementById('formr');
                                myform.addEventListener('submit', function(e) {
                                    let nameinput = document.getElementById('nom');
                                    let lnameinput = document.getElementById('prenom');
                                    let numero = document.getElementById('numero');
                                    let email = document.getElementById('email');
                                    let exp = document.getElementById('message');
                                    const regex = /^[a-zA-Z-\s]+$/;
                                    if (lnameinput.value === '') {
                                        let lnameer = document.getElementById('prenomr');
                                        lnameer.innerHTML = "le champs prenom est vide . ";
                                        lnameer.style.color = 'white';
                                        e.preventDefault();
                                    } else if (!(regex.test(lnameinput.value))) {
                                        let lnameer = document.getElementById('prenomr');
                                        lnameer.innerHTML = "le prenom doit comporter des lettres,et tirets seulements.";
                                        lnameer.style.color = 'white';
                                        e.preventDefault();
                                    }
                                    if (nameinput.value === '') {
                                        let nameer = document.getElementById('nomr');
                                        nameer.innerHTML = "le champs nom est vide . ";
                                        nameer.style.color = 'white';
                                        e.preventDefault();
                                    } else if (!(regex.test(nameinput.value))) {
                                        let nameer = document.getElementById('nomr');
                                        nameer.innerHTML = "le nom doit comporter des lettres,et tirets seulements.";
                                        nameer.style.color = 'white';
                                        e.preventDefault();
                                    }
                                    if (numero.value === '') {
                                        let numeror = document.getElementById('numeror');
                                        numeror.innerHTML = "le champs numero est vide . ";
                                        numeror.style.color = 'white';
                                        e.preventDefault();
                                    } else if (!(/^[1-9]+$/.test(numero.value))) {
                                        let numeror = document.getElementById('numeror');
                                        numeror.innerHTML = "l numero doit comporter que des numero";
                                        numeror.style.color = 'white';
                                        e.preventDefault();
                                    }
                                    if (exp.value === '') {
                                        let nameer = document.getElementById('expr');
                                        nameer.innerHTML = "le champs experience&motivation est vide . ";
                                        nameer.style.color = 'white';
                                        e.preventDefault();
                                    }

                                });
                            </script>
                            </div>

                            <div class="col-lg-6 col-12 mx-auto mt-5 mt-lg-0 ps-lg-5">
                              
                            </div>

                        </div>
                    </div>
                </section>

                <section class="barista-section section-padding section-bg" id="section_2">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                                <em class="text-white">Empreinte</em>

                                <h2 class="text-white">Meet our guides</h2>
                            </div>
                        <div class="col-lg-4 col-md-4 col-4 mb-4">
                        <?php
                            foreach ($guides as $guide) :
                            $volntaireC = new VolontaireC();
                        ?>
                            
                            <a href="toPortfolio.php?id=<?=$guide['id_portfolio']?>">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0"><?=$guide['nom']?> <?=$guide['prenom']?></h4>   
                                        </div>
                                        <p class="text-white mb-0"><?=$guide['specialite']?></p>
                                        
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="images/<?=$guide['photo']?>" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                            </a>
                            <?php                             
                                $volntaireC = new VolontaireC();
                            endforeach;
                        ?>
                        </div>
                    </div>
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
                                        <a href="#" class="social-icon-link bi-star">
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

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/vegas.min.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>