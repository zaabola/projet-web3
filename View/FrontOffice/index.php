<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "empreinte1";

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

        <title>SHOP</title>

        <!-- CSS FILES -->                
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
            
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/vegas.min.css" rel="stylesheet">

        
        <link rel="stylesheet" href="css/tooplate-barista.css">
        <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
        <link rel="icon" href="logo.png">
</head>

<body>
<main>
<nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.html">
                            <img src="logo.png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
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
                                    <a href="#section_69" class="nav-link click-scroll">Shop</a>
                                </li>
                               

                              

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">Reclamation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="panier.php">Panier</a>
                                </li>
                            </ul>

                            <button id="lang-switch" class="btn btn-outline-warning me-2">Switch Language</button>

                        </div>
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
                            <img src="chachia.png" class="team-block-image" alt="Chachia">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                            <img src="OIP (3).jpg" class="team-block-image img-fluid" alt="Zarbiya Karwiya">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                            <img src="R.jpg" class="team-block-image img-fluid" alt="Kholkhal">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                            <img src="mystic-kabbalah-hand-of-khamsa-necklace_1024x1024.webp" class="team-block-image img-fluid" alt="Khamsa">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                            <img src="Hannibal_Barca_bust_from_Capua_photo (1).jpg" class="team-block-image img-fluid" alt="Hannibal's Statue">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                            <img src="jebba.jpg" class="team-block-image img-fluid" alt="Jebba">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                        <form method="POST" action="index.php">
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
                                    <form class="custom-form booking-form" action="../../Controller/reservation.php" method="post" role="form" onsubmit="return verifyInputs()">
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
            fetch('index.php', {
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
            fetch('index.php', {
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
            fetch('index.php', {
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
            fetch('index.php')
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
    <script>document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        en: {
            'welcome': 'Welcome to Our Web Site بصمة',
            'our_story': 'Our Story',
            'check_store': 'Check Store',
            'reclamation_heading': 'Reclamation',
            'reclamation_text': 'If you want to make a complaint contact us at 99888777 or you can visit our shop',
            'or_visit_shop': 'or you can visit our shop',
            'where_to_find': 'Where to find us?',
            'contact': 'Contact',
            'phone': 'Phone:',
            'email': 'Email:',
            'opening_hours': 'Opening Hours',
            'monday_friday': 'Monday - Friday',
            'saturday': 'Saturday',
            'sunday': 'Sunday',
            'closed': 'Closed',
            'switch_lang': 'Switch to French',
            'add_to_cart': 'Add to cart',
            'home': 'Home',
            'shop': 'Shop',
            'reclamation_nav': 'Reclamation',
            'panier': 'Panier',
            'experience': 'Experience The History Of Tunisia',
            'shop_heading': 'Shop',
            'all': 'All',
            'price_asc': 'Price: Low to High',
            'price_desc': 'Price: High to Low',
            'popularity': 'Most Popular',
            'all_categories': 'All Categories',
            'accessories': 'Accessories',
            'clothing': 'Clothing',
            'decor': 'Decor'
        },
        fr: {
            'welcome': 'Bienvenue sur notre site Web بصمة',
            'our_story': 'Notre Histoire',
            'check_store': 'Consulter la boutique',
            'reclamation_heading': 'Réclamation',
            'reclamation_text': 'Si vous voulez faire une réclamation contactez-nous au 99888777 ou vous pouvez visiter notre boutique',
            'or_visit_shop': 'ou vous pouvez visiter notre boutique',
            'where_to_find': 'Où nous trouver?',
            'contact': 'Contact',
            'phone': 'Téléphone:',
            'email': 'E-mail:',
            'opening_hours': 'Heures d\'ouverture',
            'monday_friday': 'Lundi - Vendredi',
            'saturday': 'Samedi',
            'sunday': 'Dimanche',
            'closed': 'Fermé',
            'switch_lang': 'Passer à l\'anglais',
            'add_to_cart': 'Ajouter au panier',
            'home': 'Accueil',
            'shop': 'Boutique',
            'reclamation_nav': 'Réclamation',
            'panier': 'Panier',
            'experience': 'Découvrez l\'histoire de la Tunisie',
            'shop_heading': 'Boutique',
            'all': 'Tout',
            'price_asc': 'Prix: du plus bas au plus élevé',
            'price_desc': 'Prix: du plus élevé au plus bas',
            'popularity': 'Les plus populaires',
            'all_categories': 'Toutes les catégories',
            'accessories': 'Accessoires',
            'clothing': 'Vêtements',
            'decor': 'Décor'
        }
    };

    let currentLang = 'en';

    document.getElementById('lang-switch').addEventListener('click', () => {
        currentLang = currentLang === 'en' ? 'fr' : 'en';
        updateText();
    });

    function updateText() {
        document.querySelector('.hero-section .small-text').textContent = translations[currentLang]['welcome'];
        document.querySelector('.btn.custom-btn.custom-border-btn').textContent = translations[currentLang]['our_story'];
        document.querySelector('.btn.custom-btn.smoothscroll').textContent = translations[currentLang]['check_store'];
        document.querySelector('.contact-section h2').textContent = translations[currentLang]['reclamation_heading'];
        document.querySelector('.contact-section em').textContent = translations[currentLang]['reclamation_text'];
        document.querySelector('.site-footer .text-white.d-block.mb-4').textContent = translations[currentLang]['where_to_find'];
        document.querySelector('.site-footer .d-flex.mb-1 strong').textContent = translations[currentLang]['phone'];
        document.querySelector('.site-footer .d-flex strong').textContent = translations[currentLang]['email'];
        document.querySelector('.opening-hours-list').previousElementSibling.textContent = translations[currentLang]['opening_hours'];

        // Update opening hours
        const openingHoursList = document.querySelectorAll('.opening-hours-list li');
        openingHoursList[0].childNodes[0].textContent = translations[currentLang]['monday_friday'];
        openingHoursList[1].childNodes[0].textContent = translations[currentLang]['saturday'];
        openingHoursList[2].childNodes[0].textContent = translations[currentLang]['sunday'];
        openingHoursList[2].childNodes[2].textContent = translations[currentLang]['closed'];

        // Update menu items
        document.querySelectorAll('.nav-link.click-scroll')[0].textContent = translations[currentLang]['home'];
        document.querySelectorAll('.nav-link.click-scroll')[1].textContent = translations[currentLang]['shop'];
        document.querySelectorAll('.nav-link.click-scroll')[2].textContent = translations[currentLang]['reclamation_nav'];
        document.querySelectorAll('.nav-link.click-scroll')[3].textContent = translations[currentLang]['panier'];

        // Update section headings
        document.querySelector('.barista-section em.text-white').textContent = translations[currentLang]['experience'];
        document.querySelector('.barista-section h2.text-white').textContent = translations[currentLang]['shop_heading'];

        // Update filter buttons and select options
        document.querySelectorAll('.filter-btn[data-filter="all"]').forEach(btn => btn.textContent = translations[currentLang]['all']);
        document.querySelectorAll('.filter-btn[data-filter="price-asc"]').forEach(btn => btn.textContent = translations[currentLang]['price_asc']);
        document.querySelectorAll('.filter-btn[data-filter="price-desc"]').forEach(btn => btn.textContent = translations[currentLang]['price_desc']);
        document.querySelectorAll('.filter-btn[data-filter="popularity"]').forEach(btn => btn.textContent = translations[currentLang]['popularity']);
        
        document.getElementById('category-filter').querySelectorAll('option')[0].textContent = translations[currentLang]['all_categories'];
        document.getElementById('category-filter').querySelectorAll('option')[1].textContent = translations[currentLang]['accessories'];
        document.getElementById('category-filter').querySelectorAll('option')[2].textContent = translations[currentLang]['clothing'];
        document.getElementById('category-filter').querySelectorAll('option')[3].textContent = translations[currentLang]['decor'];

        // Update additional text
        document.querySelectorAll('.contact-section em')[1].textContent = translations[currentLang]['or_visit_shop'];

        // Update all buttons
        document.querySelectorAll('button, .btn').forEach(button => {
            switch(button.id) {
                case 'lang-switch':
                    button.textContent = translations[currentLang]['switch_lang'];
                    break;
                default:
                    if (button.textContent.trim() === 'Add to cart' || button.textContent.trim() === 'Ajouter au panier') {
                        button.textContent = translations[currentLang]['add_to_cart'];
                    }
            }
        });
    }

    updateText(); // Call to ensure the text is correctly set on page load
});
</script>



    </body>
</html>