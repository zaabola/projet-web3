<?php
require_once 'c:/xampp/htdocs/projet/view/Backoffice/commande.php'; // Include the Commande class

$host = "localhost";
$username = "root";
$password = "";
$dbname = "empreinte";

// Database connection
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Create an instance of the Commande class
$commande = new Commande($db);

// Handle different actions based on the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new order
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $Adresse_client = $_POST['Adresse_client'];
        $Tel_client = $_POST['Tel_client'];
        $Nom_client = $_POST['Nom_client'];
        $Prenom_client = $_POST['Prenom_client'];

        if ($commande->createCommande($Adresse_client, $Tel_client, $Nom_client, $Prenom_client)) {
            echo "Order created successfully!";
        } else {
            echo "Failed to create order.";
        }
    }

    // Update an existing order
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $Id_commande = $_POST['Id_commande'];
        $Adresse_client = $_POST['Adresse_client'];
        $Tel_client = $_POST['Tel_client'];
        $Nom_client = $_POST['Nom_client'];
        $Prenom_client = $_POST['Prenom_client'];

        if ($commande->updateCommande($Id_commande, $Adresse_client, $Tel_client, $Nom_client, $Prenom_client)) {
            echo "Order updated successfully!";
        } else {
            echo "Failed to update order.";
        }
    }

    // Delete an order
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $Id_commande = $_POST['Id_commande'];

        if ($commande->deleteCommande($Id_commande)) {
            echo "Order deleted successfully!";
        } else {
            echo "Failed to delete order.";
        }
    }
}

// Handle reading orders
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all orders
    $orders = $commande->getAllCommandes();
    echo json_encode($orders); // Return orders as JSON
}

// Example of getting a specific order by ID
if (isset($_GET['Id_commande'])) {
    $Id_commande = $_GET['Id_commande'];
    $order = $commande->getCommandeById($Id_commande);
    echo json_encode($order); // Return the specific order as JSON
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
                            </ul>

                            
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
                                <em class="text-white">Experience The History Of Tuinisia </em>

                                <h2 class="text-white">Shop</h2>
                            </div>

                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">Chachia</h4>

                                            <p class="badge ms-4"><em>10DT</em></p>
                                        </div>

                                        <p class="text-white mb-0">The chechia is a traditional headgear worn in Tuinisia.</p>
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="chachia.png" class="team-block-image img-fluid" alt="">
                                    </div>
                                    
                                </div>
                                <br>
                                
               
            
        </div>

       


<div class="col-lg-3 col-md-6 col-12 mb-4">
    <div class="team-block-wrap">
        <div class="team-block-info d-flex flex-column">
            <div class="d-flex mt-auto mb-3">
                <h4 class="text-white mb-0">Zarbiya Karwiya</h4>
                <p class="badge ms-4"><em>200DT</em></p>
            </div>
            <p class="text-white mb-0">Big deals! upholstery fabric kilim orange fabric kilim boho armchair fabric kilim bohemian tribal persian ethnic rug kilim by the yard meter chair sofa Hurry.</p>
        </div>
        <div class="team-block-image-wrap">
            <img src="OIP (3).jpg" class="team-block-image img-fluid" alt="">
        </div>
    </div>
    
   

</div>

                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">Kholkhal</h4>

                                            <p class="badge ms-4"><em>60dt</em></p>
                                        </div>

                                        <p class="text-white mb-0">The Kholkhal is a traditional accessory worn by women in Tunisia for centuries.</p>
                                    </div>
                                    

                                    <div class="team-block-image-wrap">
                                        <img src="R.jpg" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                                
                            
                               
                            </div>

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">Khamsa</h4>

                                            <p class="badge ms-4"><em>25DT</em></p>
                                        </div>

                                        <p class="text-white mb-0">The Khamsa is a symbol representing a hand, used as an amulet, talisman and jewelry by the inhabitants of North Africa..</p>
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="mystic-kabbalah-hand-of-khamsa-necklace_1024x1024.webp" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                               
                                
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">Hannibal's statue</h4>

                                            <p class="badge ms-4"><em>600DT</em></p>
                                        </div>

                                        <p class="text-white mb-0">Hannibal was a Carthaginian general and politician, generally regarded as one of the greatest military tacticians in history.</p>
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="Hannibal_Barca_bust_from_Capua_photo (1).jpg" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                                
                            
                               
                            </div>
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">Jebba</h4>

                                            <p class="badge ms-4"><em>140DT</em></p>
                                        </div>

                                        <p class="text-white mb-0">Sa fabrication artisanale est assurée par des artisans qui coupent, cousent et brodent, dans des variations tenant aux particularismes régionaux, à l'usage (quotidien ou cérémoniel) et au niveau de richesse.</p>
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="jebba.jpg" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                               
                            
                               
                            </div>


                        </div>
                    </div>
                    
                </section>
               
                <section class="booking-section section-padding">
        <div class="container">
            <div class="row">
                <div >
                    <div class="booking-form-wrap">
                        <div class="row">
                            <div class="col-lg-7 col-12 p-0">
                                <form class="custom-form booking-form" action="#" method="post" role="form" onsubmit="return verifyInputs1()" id="createOrderForm">
                                    <input type="hidden" name="formValid" id="formValid" value="false">
                                    <div class="text-center mb-4 pb-lg-2">
                                        <em class="text-white">Remplir le formulaire de commande</em>
                                        <h2 class="text-white">Acheter une Produit</h2>
                                    </div>
                                    <div class="booking-form-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-12">
                                                <input type="text" name="Prenom_client" id="Prenom_client" class="form-control" placeholder="Nom">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="text" name="Nom_client" id="Nom_client" class="form-control" placeholder="Prénom">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="tel" name="Tel_client" id="Tel_client" class="form-control" placeholder="Téléphone">
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <input type="text" name="Adresse_client" id="Adresse_client" class="form-control" placeholder="Adresse">
                                            </div>
                                            <div class="col-lg-12 col-12">
                                                <select name="destination" id="produit1" class="form-control">
                                                    <option value="" disabled selected>Choisir une Produit</option>
                                                    <option value="Tozeur">Chachia</option>
                                                    <option value="Djerba">Jebba</option>
                                                    <option value="El Jem">Kholkhal</option>
                                                    <option value="Sidi Bou Said">Zarbiya</option>
                                                    <option value="Carthage">Khomssa</option>
                                                    <option value="Tunis">Hannibaal statue</option>
                                                </select>
                                                <textarea name="commentaire" rows="3" class="form-control" placeholder="Commentaire (optionnel)"></textarea>
                                            </div>
                                            <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                                                <button type="submit" class="form-control">Acheter</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-5 col-12 p-0">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    function verifyInputs1() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        const lastName1 = document.getElementById('Prenom_client').value;
        const Adresse1 = document.getElementById('Adresse_client').value;
        const firstName1 = document.getElementById('Nom_client').value;
        const phone1 = document.getElementById('Tel_client').value;
        const produit1 = document.getElementById('produit1').value;
        let isValid = true;

        if (!lastName1) {
            showError('Prenom_client', 'Remplir champ Nom.');
            isValid = false; 
        }

        if (!firstName1) {
            showError('Nom_client', 'Remplir champ Prenom.');
            isValid = false; 
        }
        if (phone1.length !== 8) {
            showError('Tel_client', 'Le numéro doit comporter exactement 8 chiffres.');
            isValid = false; 
        }
        if (!Adresse1) {
            showError('Adresse_client', "Donne une adresse");
            isValid = false;
        }

        if (!produit1) {
            showError('produit1', "Choisir une Produit");
            isValid = false;
        }
        

        // Set the hidden input value for form validation status
        if (isValid) {
            document.getElementById('formValid').value = 'true';
        } else {
            document.getElementById('formValid').value = 'false';
        }

        return isValid; // Return false to prevent form submission if validation fails
    }

    function showError(inputId, message) {
        const inputField = document.getElementById(inputId);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.style.color = 'red'; 
        errorMessage.innerText = message;
        inputField.parentNode.insertBefore(errorMessage, inputField);
    }
</script>

                <section class="contact-section section-padding" id="section_5">
                    <div class="container">
                        <div class="row">   

                           
                            
                                <div class="col-lg-6 col-12">
                                    <form class="custom-form booking-form" action="../../Controller/reservation.php" method="post" role="form" onsubmit="return verifyInputs()">
                                            <div class="text-center mb-4 pb-lg-2">
                                                <em class="text-white">Remplir le formulaire de Reclamation</em>

                                                <h2 class="text-white">Reclamation</h2>
                                            </div>

                                            <div class="booking-form-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <input type="text" name="last-name" id="last-name69" class="form-control" placeholder="Nom">
                                                    </div>

                                                    <div class="col-lg-6 col-12">
                                                        <input type="text" name="first-name" id="first-name69" class="form-control" placeholder="Prenom">
                                                    </div>

                                                    <div class="col-lg-6 col-12">
                                                        <input class="form-control" type="text" name="mail" id="mail69" placeholder="foulenbenfoulen@xyz.com">
                                                    </div>
                                                    

                                                    <div class="col-lg-6 col-12">
                                                        <input type="text" class="form-control" name="phone" id="tel69" placeholder="tel: 25 456 789">
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        
                                                        <input class="form-control" type="text" name="message" id="message69" placeholder="Message(optional)">
                                                    </div>
                                                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                                                        <button type="submit" class="form-control">Reclamer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                </div>
                            </div>
                            
                            <script>
                              function verifyInputs() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());

    const lastName69 = document.getElementById('last-name69').value;
    const firstName69 = document.getElementById('first-name69').value;
    const email69 = document.getElementById('mail69').value;
    const phone69 = document.getElementById('tel69').value;
    
    let isValid = true;

    if (!lastName69) {
        showError('last-name69', 'Remplir champ Nom.');
        isValid = false; 
    }

    if (!firstName69) {
        showError('first-name69', 'Remplir champ Prenom.');
        isValid = false; 
    }

    if (!email69) {
        showError('mail69', 'Remplir champ email.');
        isValid = false; 
    }

    const emailPattern69 = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; 
    if (!emailPattern69.test(email69)) {
        showError('mail69', 'Email est invalide');
        isValid = false; 
    }

    if (phone69.length !== 8) {
        showError('tel69', 'Le numéro doit comporter exactement 8 chiffres.');
        isValid = false; 
    }

   

    return isValid; 
}

function showError(inputId, message) {
    const inputField = document.getElementById(inputId);
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    errorMessage.style.color = 'red'; 
    errorMessage.innerText = message;
    inputField.parentNode.insertBefore(errorMessage, inputField);
}
                            </script>
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
    </body>
</html>