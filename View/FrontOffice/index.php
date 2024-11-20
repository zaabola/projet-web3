<?php
require_once 'c:/xampp/htdocs/projet/view/Backoffice/commande.php'; // Include the Commande class
// Database connection
$host = "localhost";
$dbname = "empreinte";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$success = $error = "";


$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commande']) && isset($_POST['formValid']) && $_POST['formValid'] == 'true') {
    $Nom_client =  htmlspecialchars($_POST['first-name'] ?? '');
    $Prenom_client = htmlspecialchars($_POST['last-name'] ?? '');
    $Tel_client = htmlspecialchars($_POST['tel'] ?? '');
    $Adresse_client = htmlspecialchars($_POST['adresse'] ?? '');
    try {
        $sql = "INSERT INTO commande (`nom`, `prenom`, `numero`, `addresse`) 
                VALUES (:nom, :prenom, :numero, :adresse)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nom' => $Nom_client  ,
            ':prenom' => $Prenom_client, // Corrected variable name
            ' :numero' =>  $Tel_client, // Corrected variable name
            ':adresse' => $Adresse_client, // Corrected variable name // Corrected variable name
        ]);
        
        $success = "Commande ajoutée avec succès !";

        // Redirect to avoid resubmitting the form on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
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

        <link href="css/tooplate-barista.css" rel="stylesheet">
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
        
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_2">About</a>
                                </li>
                                
                                <li>
                                    <a href="#section_69" class="nav-link click-scroll">Shop</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_3">Our Menu</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_4">Reviews</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">Contact</a>
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
                                <button id="acheterBtn" class="animated-button">Acheter</button>
                    <div class="form-container">
                        <center>
                            <form  class="form" action="#" method="post" role="form" onsubmit="return verifyInputs()">
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
                                            <hr>
                                            <div class="payments">
                                                 <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                 <span>Subtotal:</span>
                                                <span>10DT</span>
                                                <span>Shipping:</span>
                                                <span>7DT</span>
                                                <span>Tax:</span>
                                                <span>3DT</span>
                                                </div>
                                             </div>
                                <hr>
                                <button type="submit" class="animated-button">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                </button>
                                <div>
                                                <div class="footer">
                                                  <label>Price:20DT</label>
                                                </div>
                                            </div>
                            </form>
                        </center>
                    </div>
               
            
        </div>
        <script>
                const acheterBtn = document.getElementById('acheterBtn');
    const formContainer = document.querySelector('.form-container');
    

    // Show/Hide the form on button click
    acheterBtn.addEventListener('click', function () {
        formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    });

    // Attach form submit event handler
    
    function verifyInputs() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        const lastName = document.getElementById('last-name').value;
        const firstName = document.getElementById('first-name').value;
        const phone = document.getElementById('tel').value;
        let isValid = true;

        if (!lastName) {
            showError('last-name', 'Remplir champ Nom.');
            isValid = false; 
        }

        if (!firstName) {
            showError('first-name', 'Remplir champ Prenom.');
            isValid = false; 
        }

        if (phone.length !== 8) {
            showError('tel', 'Le numéro doit comporter exactement 8 chiffres.');
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
    <br>
    <button id="acheterBtn1" class="animated-button">Acheter</button>
    <div class="form-container1" style="display: none;">
        <center>
        <form method="POST" action="index.php" class="form" id="form2">
                                <div class="flex">
                                    <label>
                                        <input name="Prenom_client" id="first-name1" class="input" type="text" placeholder="">
                                        <span>First Name</span>
                                    </label>
                                    <label>
                                        <input name="Nom_client" id="last-name1" class="input" type="text" placeholder="" >
                                        <span>Last Name</span>
                                    </label>
                                </div>
                                <label>
                                    <input name="Tel_client" id="tel1" class="input" type="text" placeholder="" pattern="\d{8}" >
                                    <span>Contact Number</span>
                                </label>
                                <label>
                                    <textarea name="Adresse_client" class="input01" placeholder="" rows="3"></textarea>
                                    <span>Address</span>
                                </label>
                                <hr>
                                            <div class="payments">
                                                 <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                 <span>Subtotal:</span>
                                                <span>200DT</span>
                                                <span>Shipping:</span>
                                                <span>7DT</span>
                                                <span>Tax:</span>
                                                <span>3DT</span>
                                                </div>
                                             </div>
                                <hr>
                                <button type="submit" class="animated-button" onclick="submitForm1('form2')">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                </button>
                                <div>
                                                <div class="footer">
                                                  <label>Price:210DT</label>
                                                </div>
                                            </div>
                            </form>
        </center>
    </div>
   
<script>
    const acheterBtn1 = document.getElementById('acheterBtn1');
    const formContainer1 = document.querySelector('.form-container1');
    const form1 = document.getElementById('form2');

    // Show/Hide the form on button click
    acheterBtn1.addEventListener('click', function () {
        formContainer1.style.display = formContainer1.style.display === 'none' ? 'block' : 'none';
    });

    // Attach form submit event handler
    form1.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent default form submission to handle validation
        submitForm1();  // Call the submitForm function for validation
    });

    function submitForm1() {
        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        // Get form values
        const lastName1 = document.getElementById('last-name1').value;
        const firstName1 = document.getElementById('first-name1').value;
        const phone1 = document.getElementById('tel1').value;
        const address1 = document.querySelector('textarea[name="Adresse_client1"]').value;

        let isValid = true;

        // Validate the inputs
        if (!lastName1) {
            showError('last-name1', 'Remplir champ Nom.');
            isValid = false;
        }

        if (!firstName1) {
            showError('first-name1', 'Remplir champ Prenom.');
            isValid = false;
        }

        if (!phone1) {
            showError('tel1', 'Remplir champ téléphone.');
            isValid = false;
        }

        if (phone1.length !== 8) {
            showError('tel1', 'Le numéro doit comporter exactement 8 chiffres.');
            isValid = false;
        }

        if (!address) {
            showError('Adresse_client1', 'Remplir champ adresse.');
            isValid = false;
        }

        // If all fields are valid, simulate form submission
        if (isValid) {
            alert('Form submitted successfully!');
            form.reset();  // Reset the form after submission
            formContainer1.style.display = 'none';  // Hide the form after submission
        }
    }

    function showError(inputId, message) {
        const inputField = document.getElementById(inputId);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.style.color = 'red'; 
        errorMessage.innerText = message;
        inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling);  // Insert error message after the input field
    }
</script>
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
                                <br>
                                <button id="acheterBtn2" class="animated-button">Acheter</button>
                                <div  class="form-container2">
                                    <center>
                                        <form class="form" id="form3">
                                            <div class="flex">
                                                <label>
                                                    <input id="first-name2" class="input" type="text" placeholder="">
                                                    <span>first name</span>
                                                </label>
                                                <label>
                                                    <input id="last-name2" class="input" type="text" placeholder="">
                                                    <span>last name</span>
                                                </label>
                                            </div>
                                            <label>
                                                <input id="mail2" class="input" type="email" placeholder="">
                                                <span>email</span>
                                            </label>
                                            <label>
                                                <input id="tel2" class="input" placeholder="" type="tel">
                                                <span>contact number</span>
                                            </label>
                                            <label>
                                                <textarea class="input01" placeholder="" rows="3" style="height: 70%;"></textarea>
                                                <span>Address</span>
                                            </label>
                                            <hr>
                                            <div class="payments">
                                                <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                    <span>Subtotal:</span>
                                                    <span>60DT</span>
                                                    <span>Shipping:</span>
                                                    <span>7DT</span>
                                                    <span>Tax:</span>
                                                    <span>3DT</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="button" class="animated-button" onclick="submitForm2('form3')">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                            </button>
                                            <div>
                                                <div class="footer">
                                                    <label>Price:70DT</label>
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </center>
                                </div>
                            
                                <script>
                                    const acheterBtn2 = document.getElementById('acheterBtn2');
                                   const formContainer2 = document.querySelector('.form-container2');
                           
                                   acheterBtn2.addEventListener('click', function() {
                                       formContainer2.style.display = formContainer2.style.display === 'none' ? 'block' : 'none';
                                   });
                           
                                   function submitForm2(formId) {
                                       const form = document.getElementById(formId);
                                       const errorMessages = document.querySelectorAll('.error-message');
                                       errorMessages.forEach(msg => msg.remove()); // Clear previous error messages
                           
                                       const lastName2 = document.getElementById('last-name2').value;
                                       const firstName2 = document.getElementById('first-name2').value;
                                       const email2 = document.getElementById('mail2').value;
                                       const phone2 = document.getElementById('tel2').value;
                           
                                       let isValid = true;
                           
                                       if (!lastName2) {
                                           showError('last-name2', 'Remplir champ Nom.');
                                           isValid = false; 
                                       }
                           
                                       if (!firstName2) {
                                           showError('first-name2', 'Remplir champ Prenom.');
                                           isValid = false; 
                                       }
                           
                                       if (!email2) {
                                           showError('mail2', 'Remplir champ email.');
                                           isValid = false; 
                                       }
                                       if (!phone2) {
                                           showError('tel2', 'Remplir champ phone.');
                                           isValid = false; 
                                       }
                           
                                       const emailPattern2 = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; 
                                       if (!emailPattern2.test(email2)) {
                                           showError('mail2', 'Email est invalide');
                                           isValid = false; 
                                       }
                           
                                       if (phone2.length !== 8) {
                                           showError('tel2', 'Le numéro doit comporter exactement 8 chiffres.');
                                                           isValid = false; 
                                       }
                           
                                       if (isValid) {
                                           // Here you can handle the form submission, e.g., send data to the server
                                           alert('Form submitted successfully!');
                                           form.reset(); // Reset the form after submission
                                           formContainer2.style.display = 'none'; // Hide the form after submission
                                       }
                                   }
                           
                                   function showError(inputId, message) {
                                       const inputField = document.getElementById(inputId);
                                       const errorMessage = document.createElement('div');
                                       errorMessage.className = 'error-message';
                                       errorMessage.style.color = 'red'; 
                                       errorMessage.innerText = message;
                                       inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling); // Insert error message after the input field
                                   }
                               </script>
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
                                <br>
                                <button id="acheterBtn3" class="animated-button">Acheter</button>
                                <div  class="form-container3">
                                    <center>
                                        <form class="form" id="form4">
                                            <div class="flex">
                                                <label>
                                                    <input id="first-name3" class="input" type="text" placeholder="">
                                                    <span>first name</span>
                                                </label>
                                                <label>
                                                    <input id="last-name3" class="input" type="text" placeholder="">
                                                    <span>last name</span>
                                                </label>
                                            </div>
                                            <label>
                                                <input id="mail3" class="input" type="email" placeholder="">
                                                <span>email</span>
                                            </label>
                                            <label>
                                                <input id="tel3" class="input" placeholder="" type="tel">
                                                <span>contact number</span>
                                            </label>
                                            <label>
                                                <textarea class="input01" placeholder="" rows="3" style="height: 70%;"></textarea>
                                                <span>Address</span>
                                            </label>
                                            <hr>
                                            <div class="payments">
                                                <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                    <span>Subtotal:</span>
                                                    <span>25DT</span>
                                                    <span>Shipping:</span>
                                                    <span>7DT</span>
                                                    <span>Tax:</span>
                                                    <span>3DT</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="button" class="animated-button" onclick="submitForm3('form4')">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                            </button>
                                            <div>
                                                <div class="footer">
                                                    <label>Price:35DT</label>
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </center>
                                </div>
                            
                                <script>
                                    const acheterBtn3 = document.getElementById('acheterBtn3');
                                   const formContainer3 = document.querySelector('.form-container3');
                           
                                   acheterBtn3.addEventListener('click', function() {
                                       formContainer3.style.display = formContainer3.style.display === 'none' ? 'block' : 'none';
                                   });
                           
                                   function submitForm3(formId) {
                                       const form = document.getElementById(formId);
                                       const errorMessages = document.querySelectorAll('.error-message');
                                       errorMessages.forEach(msg => msg.remove()); // Clear previous error messages
                           
                                       const lastName3 = document.getElementById('last-name3').value;
                                       const firstName3 = document.getElementById('first-name3').value;
                                       const email3 = document.getElementById('mail3').value;
                                       const phone3 = document.getElementById('tel3').value;
                           
                                       let isValid = true;
                           
                                       if (!lastName3) {
                                           showError('last-name3', 'Remplir champ Nom.');
                                           isValid = false; 
                                       }
                           
                                       if (!firstName3) {
                                           showError('first-name3', 'Remplir champ Prenom.');
                                           isValid = false; 
                                       }
                           
                                       if (!email3) {
                                           showError('mail3', 'Remplir champ email.');
                                           isValid = false; 
                                       }
                                       if (!phone3) {
                                           showError('tel3', 'Remplir champ phone.');
                                           isValid = false; 
                                       }
                           
                                       const emailPattern2 = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; 
                                       if (!emailPattern2.test(email3)) {
                                           showError('mail3', 'Email est invalide');
                                           isValid = false; 
                                       }
                           
                                       if (phone3.length !== 8) {
                                           showError('tel3', 'Le numéro doit comporter exactement 8 chiffres.');
                                                           isValid = false; 
                                       }
                           
                                       if (isValid) {
                                           // Here you can handle the form submission, e.g., send data to the server
                                           alert('Form submitted successfully!');
                                           form.reset(); // Reset the form after submission
                                           formContainer3.style.display = 'none'; // Hide the form after submission
                                       }
                                   }
                           
                                   function showError(inputId, message) {
                                       const inputField = document.getElementById(inputId);
                                       const errorMessage = document.createElement('div');
                                       errorMessage.className = 'error-message';
                                       errorMessage.style.color = 'red'; 
                                       errorMessage.innerText = message;
                                       inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling); // Insert error message after the input field
                                   }
                               </script>
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
                                <br>
                                <button id="acheterBtn4" class="animated-button">Acheter</button>
                                <div  class="form-container4">
                                    <center>
                                        <form class="form" id="form5">
                                            <div class="flex">
                                                <label>
                                                    <input id="first-name4" class="input" type="text" placeholder="">
                                                    <span>first name</span>
                                                </label>
                                                <label>
                                                    <input id="last-name4" class="input" type="text" placeholder="">
                                                    <span>last name</span>
                                                </label>
                                            </div>
                                            <label>
                                                <input id="mail4" class="input" type="email" placeholder="">
                                                <span>email</span>
                                            </label>
                                            <label>
                                                <input id="tel4" class="input" placeholder="" type="tel">
                                                <span>contact number</span>
                                            </label>
                                            <label>
                                                <textarea class="input01" placeholder="" rows="3" style="height: 70%;"></textarea>
                                                <span>Address</span>
                                            </label>
                                            <hr>
                                            <div class="payments">
                                                <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                    <span>Subtotal:</span>
                                                    <span>25DT</span>
                                                    <span>Shipping:</span>
                                                    <span>7DT</span>
                                                    <span>Tax:</span>
                                                    <span>3DT</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="button" class="animated-button" onclick="submitForm4('form5')">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                            </button>
                                            <div>
                                                <div class="footer">
                                                    <label>Price:35DT</label>
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </center>
                                </div>
                            
                                <script>
                                    const acheterBtn4 = document.getElementById('acheterBtn4');
                                   const formContainer4 = document.querySelector('.form-container4');
                           
                                   acheterBtn3.addEventListener('click', function() {
                                       formContainer3.style.display = formContainer3.style.display === 'none' ? 'block' : 'none';
                                   });
                           
                                   function submitForm4(formId) {
                                       const form = document.getElementById(formId);
                                       const errorMessages = document.querySelectorAll('.error-message');
                                       errorMessages.forEach(msg => msg.remove()); // Clear previous error messages
                           
                                       const lastName4 = document.getElementById('last-name4').value;
                                       const firstName4 = document.getElementById('first-name4').value;
                                       const email4 = document.getElementById('mail4').value;
                                       const phone4 = document.getElementById('tel4').value;
                           
                                       let isValid = true;
                           
                                       if (!lastName4) {
                                           showError('last-name4', 'Remplir champ Nom.');
                                           isValid = false; 
                                       }
                           
                                       if (!firstName4) {
                                           showError('first-name4', 'Remplir champ Prenom.');
                                           isValid = false; 
                                       }
                           
                                       if (!email4) {
                                           showError('mail4', 'Remplir champ email.');
                                           isValid = false; 
                                       }
                                       if (!phone4) {
                                           showError('tel4', 'Remplir champ phone.');
                                           isValid = false; 
                                       }
                           
                                       const emailPattern4 = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; 
                                       if (!emailPattern4.test(email4)) {
                                           showError('mail4', 'Email est invalide');
                                           isValid = false; 
                                       }
                           
                                       if (phone4.length !== 8) {
                                           showError('tel4', 'Le numéro doit comporter exactement 8 chiffres.');
                                                           isValid = false; 
                                       }
                           
                                       if (isValid) {
                                           // Here you can handle the form submission, e.g., send data to the server
                                           alert('Form submitted successfully!');
                                           form.reset(); // Reset the form after submission
                                           formContainer4.style.display = 'none'; // Hide the form after submission
                                       }
                                   }
                           
                                   function showError(inputId, message) {
                                       const inputField = document.getElementById(inputId);
                                       const errorMessage = document.createElement('div');
                                       errorMessage.className = 'error-message';
                                       errorMessage.style.color = 'red'; 
                                       errorMessage.innerText = message;
                                       inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling); // Insert error message after the input field
                                   }
                               </script>
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
                                <br>
                                <button id="acheterBtn5" class="animated-button">Acheter</button>
                                <div  class="form-container5">
                                    <center>
                                        <form class="form">
                                            <div class="flex">
                                                <label>
                                                    <input class="input" type="text" placeholder="" required="">
                                                    <span>first name</span>
                                                </label>
                                                <label>
                                                    <input class="input" type="text" placeholder="" required="">
                                                    <span>last name</span>
                                                </label>
                                            </div>  
                                            <label>
                                                <input class="input" type="email" placeholder="" required="">
                                                <span>email</span>
                                            </label> 
                                            <label>
                                                <input class="input" placeholder="" type="tel" required="">
                                                <span>contact number</span>
                                            </label>
                                            <label>
                                                <textarea class="input01" placeholder="" rows="3" required="" style="height: 70%;"></textarea>
                                                <span>Address</span>
                                            </label>
                                            <hr>
                                            <div class="payments">
                                                 <span>PAYMENT</span>
                                                <div class="details" style="height: 70%;">
                                                 <span>Subtotal:</span>
                                                <span>140DT</span>
                                                <span>Shipping:</span>
                                                <span>7DT</span>
                                                <span>Tax:</span>
                                                <span>3DT</span>
                                                </div>
                                             </div>
                                             <hr>
                                            
                                            <button type="submit" class="animated-button">
                                                <span class="top-key"></span>
                                                <span style="color: black;">submit</span>
                                                <span class="bottom-key-1"></span>
                                                <span class="bottom-key-2"></span>
                                            </button>
                                            <div>
                                                <div class="footer">
                                                  <label>Price:150DT</label>
                                                </div>
                                            </div>
                                        </form>
                                       
                                    </center>
                                </div>
                            
                                <script>
                                    const acheterBtn5 = document.getElementById('acheterBtn4');
                                    const formContainer5 = document.querySelector('.form-container4');
                            
                                    acheterBtn4.addEventListener('click', function() {
                                        if (formContainer4.style.display === 'none') {
                                            formContainer4.style.display = 'block';
                                        } else {
                                            formContainer4.style.display = 'none';
                                        }
                                    });
                            
                                    formContainer4.addEventListener('click', function(event) {
                                        if (event.target === formContainer4) {
                                            formContainer4.style.display = 'none';
                                        }
                                    });
                                </script>
                            </div>


                        </div>
                    </div>
                    
                </section>
               


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
                                                        <button type="submit" class="form-control">Reserver</button>
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

    </body>
</html>