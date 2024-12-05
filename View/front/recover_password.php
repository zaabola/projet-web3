<?php
session_start();
require_once "../../config.php";

// Fonction pour envoyer le SMS via Infobip
function sendSMS($phoneNumber, $message) {
    // Remplacez par votre clé API Infobip
    $apiKey = '038633abbb0672d0fd0327e7e24232dc-66d06952-14a6-43b0-bfe3-f11603fb7bb7';

    // URL de l'API Infobip pour envoyer des SMS
    $url = 'https://api.infobip.com/sms/2/text/advanced';

    // Données du SMS
    $data = [
        'messages' => [
            [
                'from' => 'Basma', // Expéditeur
                'destinations' => [
                    ['to' => $phoneNumber] // Numéro du destinataire
                ],
                'text' => $message, // Message à envoyer
            ]
        ]
    ];

    // Initialisation de la requête cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: App $apiKey", // Authentification
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Exécution de la requête
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Vérification de la réponse
    if ($httpCode === 200) {
        return true;
    } else {
        return false;
    }
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["recover"])) {
    $email = trim($_POST["email"]);

    // Contrôle de saisie
    if (empty($email)) {
        echo '<div class="alert alert-danger">L\'email doit être renseigné.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger">L\'adresse email n\'est pas valide.</div>';
    } else {
        // Récupérer les données de l'utilisateur à partir de l'email
        try {
            $stmt = config::getConnexion()->prepare("SELECT id, nom, email, mdp, telephone FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Si un utilisateur est trouvé, récupérer le mot de passe et le numéro de téléphone
                $password = $user["mdp"];
                $phoneNumber = $user["telephone"];

                // Envoi du mot de passe par SMS
                $smsSent = sendSMS($phoneNumber, "suite à votre demande de recupérer le mot de passe on vous envoie /n
                Votre mot de passe est : $password");

                if ($smsSent) {
                    echo '<div class="alert alert-success">Votre mot de passe a été envoyé par SMS avec succès !</div>';
                } else {
                    echo '<div class="alert alert-danger">Erreur lors de l\'envoi du SMS. Veuillez réessayer.</div>';
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
  <title>Recover Password</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
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
                <h5 class="card-title text-center">Recover Your Password</h5>
                <p class="text-center small">Enter your email to recover your password</p>
              </div>

              <!-- Formulaire de récupération de mot de passe -->
              <form method="POST" action="" class="row g-3 needs-validation">
                <div class="col-12">
                  <label for="email" class="form-label">📧 Email Address</label>
                  <input type="text" name="email" class="form-control" id="email" required>
                </div>

                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit" name="recover">Recover Password</button>
                </div>

                <div class="col-12 text-center">
                  <p class="small mb-0">Remember your password? <a href="signin.php">Login</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
