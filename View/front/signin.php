<?php
session_start();
require_once("../../config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); 

    // Contrôle de saisie
    if (empty($email) && empty($password)) {
        echo '<div class="alert alert-danger">Tous les champs doivent être remplis.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger">L\'adresse email n\'est pas valide.</div>';
    } else {
        try {
            // Connexion à la base de données
            $conn = config::getConnexion();

            // Préparer la requête
            $stmt = $conn->prepare("SELECT id, nom, email, mdp, role FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Récupérer les données de l'utilisateur
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Vérification du mot de passe (assurez-vous que mdp est haché dans votre base)
                if ($password === $user["mdp"]) {
                    // Stocker les informations en session
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["nom"];
                    $_SESSION["user_role"] = $user["role"];

                    // Rediriger selon le rôle
                    if ($user["role"] == 1) {
                        header("Location: ../back/Dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit(); // Toujours utiliser exit() après une redirection
                } else {
                    echo '<div class="alert alert-danger">Mot de passe incorrect.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Aucun utilisateur trouvé avec cet email.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erreur lors de la connexion à la base de données : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
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
        <div class="col-lg-4 col-md-6">
          <div class="card mb-3">
            <div class="card-body">
            <div class="d-flex justify-content-center py-4">
                                <a href="index.php" class="logo d-flex align-items-center w-auto">
                                    <img src="logosignin.png" alt="logo">
                                    <span class="d-none d-lg-block">Basma</span>
                                </a>
                            </div><!-- End Logo -->
              <div class="pt-4 pb-2">
                <h5 class="card-title text-center">Login to your Account</h5>
                <p class="text-center small">Enter your email and password</p>
              </div>

              <!-- Formulaire de connexion -->
              <form method="POST" action="" class="row g-3 needs-validation">
                <div class="col-12">
                  <label for="email" class="form-label">📧 Email Address</label>
                  <input type="text" name="email" class="form-control" id="email" >
                </div>

                <div class="col-12">
                  <label for="password" class="form-label">🔑 Password</label>
                  <input type="password" name="password" class="form-control" id="password" >
                </div>
                <div class="col-12 text-center">
                  <a >if you forgot your password</a>
                   <a href="recover_password.php" class="btn btn-link">Click here to recover it</a>
                </div>


                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit" name="login">Log in</button>
                </div>

                <div class="col-12 text-center">
                  <p class="small mb-0">Don't have an account ? <a href="signup.php">Create an account</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
        let isValid = true;
        const email = document.getElementById('email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
        if (!emailRegex.test(email)) {
            document.getElementById('emailError').textContent = 'Invalid email format.';
            isValid = false;
        }
    });
    </script>
</body>

</html>
