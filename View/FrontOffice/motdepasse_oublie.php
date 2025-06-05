<?php


require_once('../../Controller/userscontroller.php');
require_once('vendor/phpmailer/src/PHPMailer.php');
require_once('vendor/phpmailer/src/SMTP.php');
require_once('vendor/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];

  // Vérification du CAPTCHA
  $recaptchaResponse = $_POST['g-recaptcha-response'];
  $secretKey = '6LdgqZgqAAAAANnpsvdJ3i7U6fuBFYSBqsWM6FZH';
  $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
  $responseKeys = json_decode($response, true);

  if (intval($responseKeys["success"]) !== 1) {
    $message = "Vérification CAPTCHA échouée. Veuillez réessayer.";
    $message_type = 'error';
  } else {
    $controller = new userscontroller();
    $user = $controller->getUserByEmail($email);

    if ($user) {
      $reset_code = bin2hex(openssl_random_pseudo_bytes(16));
      $controller->saveResetCode($user['id'], $reset_code);

      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'khalilboujemaa2@gmail.com';
        $mail->Password   = 'rqoy nrsm quej iocf ';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('khalilboujemaa2@gmail.com', 'Emprunt');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset your password';
        $mail->Body    = "Click on this link  :<br>";
        $mail->Body    = "http://localhost/web/View/FrontOffice/reset_code.php?reset_code=" . $reset_code . "'>Reset password</a>";

        $mail->send();
        $message = "Mail has been sent to you.";
        $message_type = 'success';
      } catch (Exception $e) {
        error_log("Error while sending mail : {$mail->ErrorInfo}");
        $message = "Error while sending mail. Check network connection.";
        $message_type = 'error';
      }
    } else {
      $message = "No user found for this email.";
      $message_type = 'error';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Réinitialisation du mot de passe</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f8f9fa;
    }

    .container {
      display: flex;
      width: 80%;
      max-width: 900px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      flex-direction: column;
    }

    .message {
      padding: 10px;
      margin-bottom: 20px;
      text-align: center;
      border-radius: 5px;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
    }

    .image-section {
      background-image: url('assets/img/seconnecter.png');
      background-size: cover;
      background-position: center;
      width: 100%;
      height: 200px;
    }

    .form-section {
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .form-section h1 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #001973;
    }

    .form-section p {
      font-size: 14px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-section input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }

    .form-section .btn {
      width: 100%;
      padding: 12px;
      background-color: #0d42ff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .form-section .btn:hover {
      background-color: #495057;
    }

    .form-section .links {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      width: 100%;
    }

    .form-section .links a {
      color: #007bff;
      text-decoration: none;
    }

    .error-message {
      color: red;
      margin-top: 10px;
      display: none;
    }
  </style>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="controle_motdepasse_oublie.js"></script>
</head>

<body>
  <div class="container">
    <?php if ($message): ?>
      <div class="message <?php echo $message_type; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="image-section"></div>
    <div class="form-section">
      <h1>Réinitialisation du mot de passe</h1>
      <p>Please provide the email address associated with your account to recover your password</p>
      <form name="resetForm" action="" method="post" onsubmit="return validateForm()">
        <input type="email" name="email" placeholder="Email">
        <div id="error-message" class="error-message">Your email ?</div>
        <div class="g-recaptcha" data-sitekey="6LdgqZgqAAAAAJocQDFvnG2Fr_xFewU2MnJ37Eks"></div>
        <button type="submit" class="btn">Reset Password</button>
      </form>
      <div class="links">
        <a href="login.php">login</a>
      </div>
    </div>
  </div>
</body>

</html>
