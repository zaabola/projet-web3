<?php
include '../../controller/theme.php';
$travelOfferC = new ThemeController();
$list = $travelOfferC->listtheme();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Empreinte</title>

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
                <section class="barista-section section-padding section-bg" id="biblio">
                    <div class="container">
                        <div class="row justify-content-center">
                
                            <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                                <h2 class="text-white" style="text-align: center;">Bibliothèque</h2>
                            </div>
                            <?php foreach( $list as $p)
            {
            ?>
                            <!-- Div 1: L'indépendance de la Tunisie -->
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="team-block-wrap" data-bs-toggle="modal" data-bs-target="#modalTunisie">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex justify-content-center align-items-center mt-auto mb-3">
                                            <h4 class="text-white mb-0" style="text-align: center;"><?php echo $p['titre']; ?></h4>
                                        </div>
                                        <p class="text-white mb-0"><?php echo $p['description']; ?></p>
                                    </div>
                                    <!-- Conteneur pour l'image en pleine div -->
                                    <div class="team-block-image-wrap">
                                        <img src="<?php echo $p['image'];?>" class="team-block-image" alt="">
                                    </div>
                                </div>
                            </div>
                            <?php 
                           }
                          ?>
                            <!-- Div 2: Gastronomie -->
                     
                
                        </div>
                    </div>
                </section>
                
                

<!-- Modal 1: L'indépendance de la Tunisie -->
<div class="modal fade" id="modalTunisie" tabindex="-1" aria-labelledby="modalTunisieLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTunisieLabel">L'indépendance de la Tunisie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          
            <div class="modal-body">
                <h5>Les détails de l'indépendance de la Tunisie en 1956</h5>
                <p>Article complet sur la naissance d'une Tunisie libre, l'événement historique qui a marqué la fin du protectorat français et la création de la République tunisienne.</p>
                
                <!-- Liste d'articles -->
                <ul class="list-unstyled">
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="image-article-1.jpg" class="img-fluid" alt="Article 1">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 1: Le parcours vers l'indépendance</h6>
                                <p>Une analyse détaillée des événements menant à l'indépendance de la Tunisie et du rôle des figures emblématiques comme Habib Bourguiba.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="image-article-2.jpg" class="img-fluid" alt="Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: Les premières années de la République</h6>
                                <p>Une réflexion sur les premières années de la République tunisienne et les défis auxquels le pays a fait face après l'indépendance.</p>
                            </div>
                        </div>
                    </li>
                    <!-- Ajoutez autant d'articles que nécessaire -->
                </ul>
            </div>
        </div>
       
    </div>
</div>

<!-- Modal 2: Gastronomie -->
<div class="modal fade" id="modalGastronomie" tabindex="-1" aria-labelledby="modalGastronomieLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGastronomieLabel">Gastronomie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Les secrets de la gastronomie tunisienne</h5>
                <p>Un festin tunisien entre épices et convivialité. Découvrez les plats typiques de la Tunisie, les saveurs méditerranéennes et les traditions culinaires du pays.</p>
                
                <!-- Liste d'articles -->
                <ul class="list-unstyled">
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="gastronomie-article-1.jpg" class="img-fluid" alt="Gastronomie Article 1">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 1: La cuisine traditionnelle tunisienne</h6>
                                <p>Explorez les saveurs épicées et uniques des plats tunisiens traditionnels comme le couscous, le brik et le tajine.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="gastronomie-article-2.jpg" class="img-fluid" alt="Gastronomie Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: Les desserts tunisiens</h6>
                                <p>Découvrez les délices sucrés comme les baklavas, les makroudhs et autres spécialités de la pâtisserie tunisienne.</p>
                            </div>
                        </div>
                    </li>
                    <!-- Ajoutez autant d'articles que nécessaire -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Culture et Tradition -->
<div class="modal fade" id="modalCulture" tabindex="-1" aria-labelledby="modalCultureLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCultureLabel">Culture et Tradition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>L'héritage tunisien : Entre passé et présent</h5>
                <p>Explorez l'héritage culturel et les traditions tunisiennes qui ont traversé les âges. De l'architecture aux coutumes, découvrez comment la Tunisie allie modernité et histoire.</p>
                
                <!-- Liste d'articles -->
                <ul class="list-unstyled">
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="culture-article-1.jpg" class="img-fluid" alt="Culture Article 1">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 1: Les traditions populaires tunisiennes</h6>
                                <p>Découvrez les coutumes, les festivals et les traditions de la Tunisie, un mélange de cultures berbères, arabes et méditerranéennes.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="culture-article-2.jpg" class="img-fluid" alt="Culture Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: L'architecture traditionnelle tunisienne</h6>
                                <p>De la médina de Tunis aux mosquées historiques, découvrez l'architecture unique de la Tunisie qui témoigne de son riche passé.</p>
                            </div>
                        </div>
                    </li>
                    <!-- Ajoutez autant d'articles que nécessaire -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: Célébrations et Fêtes -->
<div class="modal fade" id="modalCelebrations" tabindex="-1" aria-labelledby="modalCelebrationsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCelebrationsLabel">Célébrations et Fêtes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Les fêtes tunisiennes : un mélange de joie et de spiritualité</h5>
                <p>Découvrez la magie des fêtes tunisiennes, comme l'Aïd, le Ramadan, et les autres célébrations qui sont un mélange de joie populaire, de spiritualité et de rassemblement communautaire.</p>
                
                <!-- Liste d'articles -->
                <ul class="list-unstyled">
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="celebrations-article-1.jpg" class="img-fluid" alt="Celebrations Article 1">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 1: L'Aïd en Tunisie</h6>
                                <p>Explorez la célébration de l'Aïd en Tunisie, une période de festivités religieuses et de rencontres familiales.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="celebrations-article-2.jpg" class="img-fluid" alt="Celebrations Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: La fête du Ramadan</h6>
                                <p>Une vue d'ensemble sur le Ramadan en Tunisie, de la préparation des repas aux traditions sociales qui l'accompagnent.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="celebrations-article-3.jpg" class="img-fluid" alt="Celebrations Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: La fête de l'indépendance</h6>
                                <p>Le 7 décembre 2021 , un décret présidentiel change la date de la Fête de la Révolution au 17 décembre , date du déclenchement du soulèvement populaire.</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="celebrations-article-2.jpg" class="img-fluid" alt="Celebrations Article 2">
                            </div>
                            <div class="col-md-8">
                                <h6>Article 2: La fête du Ramadan</h6>
                                <p>Une vue d'ensemble sur le Ramadan en Tunisie, de la préparation des repas aux traditions sociales qui l'accompagnent.</p>
                            </div>
                        </div>
                    </li>
                    <!-- Ajoutez autant d'articles que nécessaire -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
                
                <!-- Ajoutez les liens nécessaires pour Bootstrap CSS et JS -->
                <!-- CSS de Bootstrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                
                <!-- JS de Bootstrap (inclure jQuery et Popper.js pour que le modal fonctionne) -->
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>               
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