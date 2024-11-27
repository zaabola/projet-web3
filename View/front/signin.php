<?php
session_start();
require_once("C:/xampp/htdocs/projetuser/config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); // Correction pour correspondre au champ HTML

    // Contrôle de saisie
    if (empty($email) || empty($password)) {
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
                if (password_verify($password, $user["mdp"])) {
                    // Stocker les informations en session
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["nom"];
                    $_SESSION["user_role"] = $user["role"];

                    // Rediriger selon le rôle
                    if ($user["role"] == 1) {
                        header("Location: /projetuser/View/back/pages/Dashboard.php");
                    } else {
                        header("Location: /projetuser/View/front/index.html");
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
            background-color: #f5f5dc; /* Beige color */
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
            background-color: #3cb371; /* Soft green */
            border: none;
            border-radius: 10px; /* Rounded corners for the button */
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #2e8b57; /* Darker green on hover */
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
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="logosignin.png" alt="logo">
                                    <span class="d-none d-lg-block">Basma</span>
                                </a>
                            </div><!-- End Logo -->
              <div class="pt-4 pb-2">
                <h5 class="card-title text-center">Connexion à votre compte</h5>
                <p class="text-center small">Entrez votre email et mot de passe</p>
              </div>

              <!-- Formulaire de connexion -->
              <form method="POST" action="" class="row g-3 needs-validation">
                <div class="col-12">
                  <label for="email" class="form-label">Adresse Email</label>
                  <input type="email" name="email" class="form-control" id="email" required>
                </div>

                <div class="col-12">
                  <label for="password" class="form-label">Mot de passe</label>
                  <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit" name="login">Se connecter</button>
                </div>

                <div class="col-12 text-center">
                  <p class="small mb-0">Pas encore inscrit ? <a href="signup.php">Créer un compte</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
