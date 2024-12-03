<?php
// Inclure les fichiers nécessaires pour la base de données et les classes
include '../../Model/user.php';
include '../../Controller/userC.php';

$message = '';

if (isset($_POST['entrer'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role=0;
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $mdp = $_POST['mot_de_passe'];
    $verification_mdp = $_POST['verification'];
    $nationalite = $_POST['nationalite'];

    try {
        // Vérifier si l'e-mail existe déjà
        $pdo = config::getConnexion();
        $checkIfExists = "SELECT COUNT(*) as count FROM user WHERE email = ?";
        $stmtCheck = $pdo->prepare($checkIfExists);
        $stmtCheck->execute([$email]);
        $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Afficher un message si l'email existe
            $message = '<span style="color: red;">Cette adresse mail est déjà associée à un compte existant. Veuillez utiliser une autre adresse.</span>';
        } elseif ($mdp !== $verification_mdp) {
            // Vérifier la correspondance des mots de passe
            $message = '<span style="color: red;">Les mots de passe ne correspondent pas. Veuillez réessayer.</span>';
        } else {
            // Ajouter l'utilisateur avec tous les champs (y compris la nationalité)
            $user = new User(null, $nom, $prenom, $email, $mdp,$role, $adresse, $telephone, $nationalite);
            $userC = new UserC();
            $userC->addUser($user);

            // Succès
            echo '<script>
                    alert("Inscription réussie !");
                    window.location.href = "index.html";
                  </script>';
            exit();
        }
    } catch (Exception $e) {
        $message = '<span style="color: red;">Erreur : ' . $e->getMessage() . '</span>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sign Up - Create Your Account 🌟</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* General body styling */
        body {
            background-color: #bf9256; /* Beige color */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .min-vh-100 {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background-color: #ffffff; /* White background */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 20px;
        }
        .form-control {
            border-radius: 10px; /* Rounded corners for inputs */
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
        }
        .btn-success {
            background-color: #007bff;
            border: none;
            border-radius: 10px; /* Rounded corners for the button */
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="logosignin.png" alt="logo">
                                    <span class="d-none d-lg-block">Basma</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                            <div class="card-body">
    <h2 class="card-title">📝 Create an Account</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form id="registerForm" method="POST" novalidate autocomplete="off">
        <div class="form-group mb-3">
            <label for="name">👤 Last Name</label>
            <input type="text" class="form-control" id="name" name="nom">
            <span id="nameError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="prenom">👤 First Name</label>
            <input type="text" class="form-control" id="prenom" name="prenom">
            <span id="prenomError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="address">🏠 Address</label>
            <input type="text" class="form-control" id="adresse" name="adresse">
            <span id="adresseError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="telephone">📱 Phone Number</label>
            <input type="text" class="form-control" id="telephone" name="telephone">
            <span id="telephoneError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="email">📧 Email Address</label>
            <input type="email" class="form-control" id="email" name="email">
            <span id="emailError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="password">🔑 Password</label>
            <input type="password" class="form-control" id="password" name="mot_de_passe">
            <span id="passwordError" class="error-message"></span>
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">🔐 Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="verification">
            <span id="confirmPasswordError" class="error-message"></span>
        </div>
        <!-- Nouveau champ de sélection pour Nationalité -->
        <div class="form-group mb-3">
            <label for="nationalite">🌍 Nationalité</label>
            <select class="form-control" id="nationalite" name="nationalite" required>
                <option value="" disabled selected>Choisissez votre nationalité</option>
                <option value="Tunisienne">Tunisienne</option>
                <option value="Étrangère">Étrangère</option>
                <span id="nationaliteError" class="error-message"></span>
            </select>
            
        </div>
        <button type="submit" class="btn btn-success btn-block" name="entrer">Register</button>
    </form>
</div>

                                <div class="card-footer text-center">
                                    <p>Already have an account? <a href="signin.php" class="color: #007bff;">Login here</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        let isValid = true;

        // Clear previous error messages
        document.getElementById('nameError').textContent = '';
        document.getElementById('prenomError').textContent = '';
        document.getElementById('adresseError').textContent = '';
        document.getElementById('telephoneError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('confirmPasswordError').textContent = '';
        document.getElementById('nationaliteError').textContent = '';
        // Get form values
        const name = document.getElementById('name').value.trim();
        const prenom = document.getElementById('prenom').value.trim();
        const adresse = document.getElementById('adresse').value.trim();
        const telephone = document.getElementById('telephone').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('password_confirmation').value.trim();
        const nationalite = document.getElementById('nationalite').value.trim();
        // Validate name
        if (name === '') {
            document.getElementById('nameError').textContent = 'Last name is required.';
            isValid = false;
        }
    
        const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
        if (!nameRegex.test(name)) {
            document.getElementById('nameError').textContent = 'Le nom ne doit contenir que des lettres, espaces, apostrophes ou tirets.';
            isValid = false;
        }
        // Validate prenom (first name)
        if (prenom === '') {
            document.getElementById('prenomError').textContent = 'First name is required.';
            isValid = false;
        }
        if (!nameRegex.test(prenom)) {
            document.getElementById('prenomError').textContent = 'Le prenom ne doit contenir que des lettres, espaces, apostrophes ou tirets.';
            isValid = false;
        }
        // Validate address
        if (adresse === '') {
            document.getElementById('adresseError').textContent = 'Address is required.';
            isValid = false;
        }
        
        if (nationalite === 'Choisissez votre nationalité') {
            document.getElementById('nationaliteError').textContent = 'nationalite is required.';
            isValid = false;
        }
        // Validate telephone (phone number)
        if (telephone === '') {
            document.getElementById('telephoneError').textContent = 'Phone number is required.';
            isValid = false;
        }
        const phoneRegex = /^\+216[0-9]{8}$/;
        if (!phoneRegex.test(telephone)) {
            document.getElementById('telephoneError').textContent = 'Phone number must be of this shape +21612345678.';
            isValid = false;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            document.getElementById('emailError').textContent = 'Email is required.';
            isValid = false;
        } else if (!emailRegex.test(email)) {
            document.getElementById('emailError').textContent = 'Invalid email format.';
            isValid = false;
        }

        // Validate password
        if (password === '') {
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        }

        // Validate confirm password
        if (confirmPassword === '') {
            document.getElementById('confirmPasswordError').textContent = 'Please confirm your password.';
            isValid = false;
        } else if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });
    </script>
</body>
</html>
