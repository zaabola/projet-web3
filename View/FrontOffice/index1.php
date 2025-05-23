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
// Connexion à la base de données
$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

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
<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_to_cart':
            $id_produit = intval($_POST['id_produit']);
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            if (!isset($_SESSION['cart'][$id_produit])) {
                $_SESSION['cart'][$id_produit] = 1; // Initialize with 1
            } else {
                $_SESSION['cart'][$id_produit] += 1; // Increment if already exists
            }

            // Check if panier_items record exists for the user (assumed id_panier = 1)
            $check_sql = "SELECT * FROM panier_items WHERE id_panier = 1";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) == 0) {
                // Insert new record into panier_items if it does not exist
                $insert_sql = "INSERT INTO panier_items (id_panier) VALUES (1)";
                mysqli_query($conn, $insert_sql);
            }

            header('Location: panier.php');
            exit();
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
        <title>بصمة</title>
        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/vegas.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/tooplate-barista.css">
        <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
        <link rel="icon" href="logo.png">
        <style>
        .animated-button {
        position: relative;
        display: inline-block;
        padding: 12px 24px;
        border: none;
        font-size: 16px;
        background-color: inherit;
        border-radius: 100px;
        font-weight: 600;
        color: #ffffff40;
        box-shadow: 0 0 0 2px #ffffff20;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
        }

        .animated-button span:last-child {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        background-color: #df9412;
        border-radius: 50%;
        opacity: 0;
        transition: all 0.8s cubic-bezier(0.23, 1, 0.320, 1);
        }

        .animated-button span:first-child {
        position: relative;
        z-index: 1;
        }

        .animated-button:hover {
        box-shadow: 0 0 0 5px #df9412;
        color: #ffffff;
        }

        .animated-button:active {
        scale: 0.95;
        }

        .animated-button:hover span:last-child {
        width: 150px;
        height: 150px;
        opacity: 1;
        }
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
      <a class="navbar-brand d-flex align-items-center" href="index1.php">
        <img src="./images/logo.png" class="navbar-brand-image img-fluid">
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
          <li class="nav-item">
            <a class="nav-link" href="index.php">Reservation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index2.php">Guides</a>
          </li>
          <li>
            <a href="#section_69" class="nav-link click-scroll">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link click-scroll inactive" href="#section_3">Bibliothèque</a>
          </li>
          <li class="nav-item">
            <a class="nav-link click-scroll" href="panier.php">Panier</a>
          </li>
          <li class="nav-item">
            <a class="nav-link click-scroll" href="#section_5">Reclamation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link click-scroll" href="donation.php">Donate</a>
          </li>
        </ul>
      <div class="ms-lg-3">
      <a class="btn custom-btn custom-border-btn" href="logout.php">se deconnecter<i class="bi-arrow-up-right ms-2"></i></a>
    </div>
  </nav>

<section class="hero-section d-flex justify-content-center align-items-center" id="section_1">

  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 col-12 mx-auto">
          <em class="small-text">welcome to Our Web Site بصمة</em>  
            <h1>بصمة</h1>
            <p class="text-white mb-4 pb-lg-2">
                your <em>favourite</em> web site.
            </p>
            <a class="btn custom-btn custom-border-btn smoothscroll me-3" href="#section_2">
                Our Story
            </a>
            <a class="btn custom-btn smoothscroll me-2 mb-2" href="#section_69"><strong>Check store</strong></a>
      </div>
    </div>
  </div>

  <div class="hero-slides"></div>
</section>
<section class="barista-section section-padding section-bg" id="section_69">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                <em class="text-white">Experience The History Of Tunisia</em>
                <h2 class="text-white">Shop</h2>
            </div>

            <!-- Filters Section -->
            <div class="col-lg-12 text-center mb-4">
                <button class="btn btn-light filter-btn" style="background-color: rgba(255, 111, 0, 0.61)" data-filter="all">All</button>
                <button class="btn btn-light filter-btn" style="background-color: rgba(255, 111, 0, 0.61)" data-filter="price-asc">Price: Low to High</button>
                <button class="btn btn-light filter-btn" style="background-color: rgba(255, 111, 0, 0.61)" data-filter="price-desc">Price: High to Low</button>
                <button class="btn btn-light filter-btn" style="background-color: rgba(255, 111, 0, 0.61)" data-filter="popularity">Most Popular</button>
                <select class="btn btn-light filter-btn" style="background-color: rgba(255, 111, 0, 0.61)" id="category-filter">
                    <option value="all">All Categories</option>
                    <option value="accessories">Accessories</option>
                    <option value="clothing">Clothing</option>
                    <option value="decor">Decor</option>
                </select>
            </div>

            <!-- Products Section -->
            <div id="product-list" class="row">
                <!-- Chachia -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="10" data-popularity="50" data-category="accessories">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Chachia</h4>
                                <p class="badge ms-4"><em>10DT</em></p>
                            </div>
                            <p class="text-white mb-0">The chechia is a traditional headgear worn in Tunisia.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="./images/chachia.png" class="team-block-image" alt="Chachia">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="1">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>

                <!-- Zarbiya Karwiya -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="200" data-popularity="30" data-category="decor">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Zarbiya Karwiya</h4>
                                <p class="badge ms-4"><em>200DT</em></p>
                            </div>
                            <p class="text-white mb-0">Upholstery fabric kilim orange fabric kilim boho armchair fabric kilim bohemian tribal Persian ethnic rug.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="images/OIP (3).jpg" class="team-block-image img-fluid" alt="Zarbiya Karwiya">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="2">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>

                <!-- Kholkhal -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="60" data-popularity="40" data-category="accessories">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Kholkhal</h4>
                                <p class="badge ms-4"><em>60DT</em></p>
                            </div>
                            <p class="text-white mb-0">The Kholkhal is a traditional accessory worn by women in Tunisia for centuries.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="images/R.jpg" class="team-block-image img-fluid" alt="Kholkhal">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="3">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>

                <!-- Khamsa -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="25" data-popularity="60" data-category="accessories">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Khamsa</h4>
                                <p class="badge ms-4"><em>25DT</em></p>
                            </div>
                            <p class="text-white mb-0">The Khamsa is a symbol representing a hand, used as an amulet, talisman, and jewelry in North Africa.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="images/mystic-kabbalah-hand-of-khamsa-necklace_1024x1024.webp" class="team-block-image img-fluid" alt="Khamsa">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="4">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>
                <!-- Hannibal's Statue -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="600" data-popularity="10" data-category="decor">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Hannibal's Statue</h4>
                                <p class="badge ms-4"><em>600DT</em></p>
                            </div>
                            <p class="text-white mb-0">Hannibal was a Carthaginian general and politician, regarded as one of the greatest military tacticians in history.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="images/Hannibal_Barca_bust_from_Capua_photo (1).jpg" class="team-block-image img-fluid" alt="Hannibal's Statue">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="5">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>

                               <!-- Jebba -->
                               <div class="col-lg-3 col-md-6 col-12 mb-4 product-item" data-price="140" data-popularity="20" data-category="clothing">
                    <div class="team-block-wrap">
                        <div class="team-block-info d-flex flex-column">
                            <div class="d-flex mt-auto mb-3">
                                <h4 class="text-white mb-0">Jebba</h4>
                                <p class="badge ms-4"><em>140DT</em></p>
                            </div>
                            <p class="text-white mb-0">Artisan-made, reflecting regional variations and ceremonial or daily use.</p>
                        </div>
                        <div class="team-block-image-wrap">
                            <img src="images/jebba.jpg" class="team-block-image img-fluid" alt="Jebba">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index1.php">
                            <input type="hidden" name="id_produit" value="6">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="animated-button">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section  class="barista-section section-padding section-bg" id="section_3">
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
            <em class="text-white">know more about Tunisia</em>
            <h2 class="text-white">Library</h2>
                
                <!-- Modification du formulaire de recherche -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-6">
                        <form action="recherche.php" method="GET" class="mb-4" id="searchForm" onsubmit="return validateSearch()">
                            <div class="input-group">
                                <input type="text" name="q" id="searchInput" class="form-control" placeholder="Rechercher un mot..." >
                                <button type="submit" class="btn btn-custom">Search</button>
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
                                <img src="images/<?php echo htmlspecialchars($theme['image']); ?>" class="team-block-image" alt="Image du thème">
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

<!-- JavaScript for filtering products -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const categoryFilter = document.getElementById('category-filter');
        const productList = document.getElementById('product-list');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = button.getAttribute('data-filter');
                filterProducts(filter);
            });
        });

        categoryFilter.addEventListener('change', function() {
            const filter = categoryFilter.value;
            filterProducts(filter);
        });

        function filterProducts(filter) {
            const products = productList.querySelectorAll('.product-item');
            products.forEach(product => {
                if (filter === 'all' || product.getAttribute('data-category') === filter) {
                    product.style.display = 'block';
                } else if (filter.includes('price') || filter.includes('popularity')) {
                    const sortedProducts = Array.from(products).sort((a, b) => {
                        if (filter === 'price-asc') {
                            return a.getAttribute('data-price') - b.getAttribute('data-price');
                        } else if (filter === 'price-desc') {
                            return b.getAttribute('data-price') - a.getAttribute('data-price');
                        } else if (filter === 'popularity') {
                            return b.getAttribute('data-popularity') - a.getAttribute('data-popularity');
                        }
                    });
                    sortedProducts.forEach(product => {
                        productList.appendChild(product);
                    });
                } else {
                    product.style.display = 'none';
                }
            });
        }
    });
</script>



<script>
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            const products = document.querySelectorAll('.product-item');
            const productList = document.getElementById('product-list');
            
            let sortedProducts = Array.from(products);

            if (filter === 'price-asc') {
                sortedProducts.sort((a, b) => a.getAttribute('data-price') - b.getAttribute('data-price'));
            } else if (filter === 'price-desc') {
                sortedProducts.sort((a, b) => b.getAttribute('data-price') - a.getAttribute('data-price'));
            } else if (filter === 'popularity') {
                sortedProducts.sort((a, b) => b.getAttribute('data-popularity') - a.getAttribute('data-popularity'));
            }

            productList.innerHTML = '';
            sortedProducts.forEach(product => productList.appendChild(product));
        });
    });
</script>
   
                <section class="contact-section section-padding" id="section_5">
                    <div class="container">
                        <div class="row">   

                           
                            
                                <div class="col-lg-6 col-12">
                                    <form class="custom-form booking-form"  method="post" role="form" onsubmit="return verifyInputs()">
                                            <div class="text-center mb-4 pb-lg-2">
                                                

                                                <h2 class="text-white">Reclamation</h2>
                                                <em class="text-white">if you want to make a complaint contact us at 99888777</em>
                                                <br>
                                                <em class="text-white">or you can visit our shop</em>
                                            </div>
                                
                                        </div>
                                        <div class="col-lg-6 col-12 mx-auto mt-5 mt-lg-0 ps-lg-5">
                                        <iframe class="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5039.668141741662!2d72.81814769288509!3d19.043340656729775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c994f34a7355%3A0x2680d63a6f7e33c2!2sLover%20Point!5e1!3m2!1sen!2sth!4v1692722771770!5m2!1sen!2sth" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>  
                            </div>   
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

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/vegas.min.js"></script>
        <script src="js/custom.js"></script>
        <script>
        // Handle form submissions
        document.getElementById('createOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            fetch('index1.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error creating order:', error));
        });

        document.getElementById('updateOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'update');
            fetch('index1.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error updating order:', error));
        });

        document.getElementById('deleteOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'delete');
            fetch('index1.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchOrders();
            })
            .catch(error => console.error('Error deleting order:', error));
        });

        // Fetch all orders
        document.getElementById('fetchOrdersButton').addEventListener('click', fetchOrders);

        function fetchOrders() {
            fetch('index1.php')
                .then(response => response.json())
                .then(data => {
                    const ordersList = document.getElementById('ordersList');
                    ordersList.innerHTML = ''; // Clear previous results
                    data.forEach(order => {
                        const orderDiv = document.createElement('div');
                        orderDiv.innerHTML = `
                            <p><strong>Order ID:</strong> ${order.Id_commande}</p>
                            <p><strong>Client Address:</strong> ${order.Adresse_client}</p>
                            <p><strong>Client Phone:</strong> ${order.Tel_client}</p>
                            <p><strong>Client Name:</strong> ${order.Nom_client} ${order.Prenom_client}</p>
                        `;
                        ordersList.appendChild(orderDiv);
                    });
                })
                .catch(error => console.error('Error fetching orders:', error));
        }
    </script>
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
