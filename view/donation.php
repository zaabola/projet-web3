<?php
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['booking-form-name'] ?? '');
    $email = htmlspecialchars($_POST['booking-form-email'] ?? '');
    $amount = htmlspecialchars($_POST['booking-form-number'] ?? '');
    $message = htmlspecialchars($_POST['booking-form-message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($amount)) {
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Veuillez entrer un email valide.";
        } elseif (!is_numeric($amount) || $amount <= 0) {
            $error = "Le montant doit être un nombre positif.";
        } else {

            try {
                $sql = "INSERT INTO donation (donor_name, donor_email, donation_amount, message, donation_date) 
                        VALUES (:name, :email, :amount, :message, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':amount' => $amount,
                    ':message' => $message
                ]);
                $success = "Votre donation a été enregistrée avec succès !";
            } catch (PDOException $e) {
                $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
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
    <title>emprunt - Donation Form</title>
    <!-- CSS FILES -->                
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/tooplate-barista.css" rel="stylesheet">
    <script src="validation.js"></script>
</head>
<body class="reservation-page">
<main>
    <section class="booking-sections section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="booking-form-wrap">
                        <form class="custom-form booking-form" method="post" action="">
                            <div class="text-center mb-4 pb-lg-2">
                                <em class="text-white">Remplissez le formulaire de donation</em>
                                <h2 class="text-white">Donation</h2>
                                <?php if (!empty($success)): ?>
                                    <p class="text-success"><?= htmlspecialchars($success); ?></p>
                                <?php elseif (!empty($error)): ?>
                                    <p class="text-danger"><?= htmlspecialchars($error); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="booking-form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="booking-form-name" class="form-control" placeholder="Nom complet" >
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="booking-form-email" class="form-control" placeholder="Votre email" >
                                    </div>                           
                                    <div class="col-lg-12 col-12">
                                        <input type="text" name="booking-form-number" class="form-control" placeholder="Montant de la donation" >
                                        <textarea name="booking-form-message" rows="3" class="form-control" placeholder="Commentaire (facultatif)"></textarea>
                                    </div>
                                    <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                                        <button type="submit" class="form-control">Envoyer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>