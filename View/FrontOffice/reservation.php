<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once('session_check.php');
verifierSession();

// Debug session variables
error_log("Session content: " . print_r($_SESSION, true));

// Check ID
if (!isset($_SESSION['id'])) {
  // If ID is not in session, redirect to login page
  header("Location: ../FrontOffice/login.php");
  exit();
}
require_once(__DIR__ . '/../../Controller/GestionReservation.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables for messages
$success = $error = "";

// Check if form was submitted and data is valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-reservation']) && isset($_POST['formValid']) && $_POST['formValid'] == 'true') {
  $mail = new PHPMailer(true);
  $dateTime = new DateTime();
  $date = $dateTime->format('Y-m-d');

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khalilboujemaa2@gmail.com';
    $mail->Password = 'rqoy nrsm quej iocf';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('khalilboujemaa2@gmail.com', 'Reservation');
    $mail->addAddress($_POST['mail'], $_POST['first-name'] . ' ' . $_POST['last-name']);
    $mail->isHTML(true);
    $mail->Subject = 'Reservation Confirmation';
    $mail->Body = "<h1>Hello {$_POST['first-name']} {$_POST['last-name']}</h1>
                         <p>Your reservation has been successfully completed. You must present your QR code to access the bus.</p>
                         <p>Submission date: {$date}</p>
                         <p>Your QR code:</p>
                         <img src='https://upload.wikimedia.org/wikipedia/commons/7/78/Qrcode_wikipedia_fr_v2clean.png' style='width:150px; height:auto;'>";
    $mail->send();
  } catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
  }

  try {
    // Extract form data
    $nom = $_POST['last-name'] ?? '';
    $prenom = $_POST['first-name'] ?? '';
    $mail = $_POST['mail'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $commentaire = $_POST['commentaire'] ?? '';
    $date = new DateTime(); // Current date and time

    // Create a reservation object
    $reservation = new reservation($nom, $prenom, $mail, $tel, $destination, $commentaire, $date, null);

    // Use GestionReservation to add the reservation
    $gestionReservation = new GestionReservation();
    $gestionReservation->createReservation($reservation);

    // Set success message in session
    $_SESSION['success'] = "Reservation added successfully!";

    // Redirect to avoid resubmitting the form on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } catch (Exception $e) {
    // Set error message in session
    $_SESSION['error'] = $e->getMessage();

    // Redirect to avoid resubmitting the form on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
}

// Retrieve flash messages from session if they exist
if (isset($_SESSION['success'])) {
  $success = $_SESSION['success'];
  unset($_SESSION['success']); // Clear success message from session
}

if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']); // Clear error message from session
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Reservation Form for Excursions">
  <meta name="author" content="Your Name">
  <title>Reservation - Basma</title>

  <!-- CSS Files -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-icons.css" rel="stylesheet">
  <link href="css/vegas.min.css" rel="stylesheet">
  <link href="css/tooplate-barista.css" rel="stylesheet">
  <link rel="icon" href="logo.png">
</head>

<body class="reservation-page">
  <main>
    <!-- Navbar -->
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
              <a class="nav-link click-scroll" href="index1.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Reservation</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index2.php">Guides</a>
            </li>
            <li>
              <a class="nav-link click-scroll" href="index1.php#section_69">Shop</a>
            </li>
            <li class="nav-item">
              <a class="nav-link click-scroll inactive" href="index1.php#section_3">Library</a>
            </li>
            <li class="nav-item">
              <a class="nav-link click-scroll" href="panier.php">Cart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link click-scroll" href="index1.php#section_5">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link click-scroll" href="donation.php">Donate</a>
            </li>
          </ul>
          <div class="ms-lg-3">
            <a class="btn custom-btn custom-border-btn" href="logout.php">Log Out<i class="bi-arrow-up-right ms-2"></i></a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Reservation Form -->
    <section class="booking-section section-padding">
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-12 mx-auto">
            <div class="booking-form-wrap">
              <div class="row">
                <div class="col-lg-7 col-12 p-0">
                  <!-- Flash Messages -->
                  <?php if (!empty($success)) : ?>
                    <div class="alert alert-success">
                      <?= htmlspecialchars($success); ?>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger">
                      <?= htmlspecialchars($error); ?>
                    </div>
                  <?php endif; ?>

                  <form class="custom-form booking-form" action="#" method="post" role="form" onsubmit="return verifyInputs()" novalidate>
                    <input type="hidden" name="formValid" id="formValid" value="false">
                    <div class="text-center mb-4 pb-lg-2">
                      <em class="text-white">Fill out the reservation form</em>
                      <h2 class="text-white">Book a Trip</h2>
                    </div>
                    <div class="booking-form-body">
                      <div class="row">
                        <div class="col-lg-6 col-12">
                          <input type="text" name="last-name" id="last-name" class="form-control" placeholder="Last Name" readonly value="<?php echo $_SESSION['nom'] ?>">
                        </div>
                        <div class="col-lg-6 col-12">
                          <input type="text" name="first-name" id="first-name" class="form-control" placeholder="First Name" readonly value="<?php echo $_SESSION['prenom'] ?>">
                        </div>
                        <div class="col-lg-6 col-12">
                          <input type="email" name="mail" id="mail" class="form-control" placeholder="Email" readonly value="<?php echo $_SESSION['email'] ?>">
                        </div>
                        <div class="col-lg-6 col-12">
                          <input type="tel" name="tel" id="tel" class="form-control" placeholder="Phone">
                        </div>
                        <div class="col-lg-12 col-12">
                          <select name="destination" id="destination" class="form-control">
                            <option value="" disabled selected>Choose a destination</option>
                            <option value="Tozeur">Tozeur</option>
                            <option value="Djerba">Djerba</option>
                            <option value="El Jem">El Jem</option>
                            <option value="Sidi Bou Said">Sidi Bou Said</option>
                            <option value="Carthage">Carthage</option>
                            <option value="Tunis">Tunis</option>
                            <option value="Dougga">Dougga</option>
                            <option value="Kairouan">Kairouan</option>
                            <option value="Ain drahem et Tbarka">Ain Drahem and Tabarka</option>
                          </select>
                          <textarea name="commentaire" rows="3" class="form-control" placeholder="Comment (optional)"></textarea>
                        </div>
                        <div class="col-lg-4 col-md-10 col-8 mx-auto mt-2">
                          <button type="submit" class="form-control" name="add-reservation">Book Now</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-lg-5 col-12 p-0">
                  <img src="images/BusTrip.jpeg" class="booking-form-image img-fluid" alt="Bus Trip">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-12 me-auto">
            <em class="text-white d-block mb-4">Where to find us?</em>

            <strong class="text-white">
              <i class="bi-geo-alt me-2"></i>
              Av. Hedi Nouira Ariana, 2001
            </strong>

            <ul class="social-icon mt-4">
              <li class="social-icon-item">
                <a href="#" class="social-icon-link bi-facebook"></a>
              </li>
              <li class="social-icon-item">
                <a href="https://x.com/minthu" target="_new" class="social-icon-link bi-twitter"></a>
              </li>
              <li class="social-icon-item">
                <a href="#" class="social-icon-link bi-whatsapp"></a>
              </li>
            </ul>
          </div>

          <div class="col-lg-3 col-12 mt-4 mb-3 mt-lg-0 mb-lg-0">
            <em class="text-white d-block mb-4">Contact</em>
            <p class="d-flex mb-1">
              <strong class="me-2">Phone:</strong>
              <a href="tel: 305-240-9671" class="site-footer-link">
                (216)
                95 020 030
              </a>
            </p>
            <p class="d-flex">
              <strong class="me-2">Email:</strong>
              <a href="mailto:info@yourgmail.com" class="site-footer-link">
                Basma.Travel@gmail.com
              </a>
            </p>
          </div>

          <div class="col-lg-5 col-12">
            <em class="text-white d-block mb-4">Working Hours</em>
            <ul class="opening-hours-list">
              <li class="d-flex">
                Monday - Friday
                <span class="underline"></span>
                <strong>9:00 AM - 6:00 PM</strong>
              </li>
              <li class="d-flex">
                Saturday
                <span class="underline"></span>
                <strong>9:00 AM - 1:00 PM</strong>
              </li>
              <li class="d-flex">
                Sunday
                <span class="underline"></span>
                <strong>Closed</strong>
              </li>
            </ul>
          </div>

          <div class="col-lg-8 col-12 mt-4">
            <p class="copyright-text mb-0">Copyright © Basma </p>
          </div>
        </div>
      </div>
    </footer>
  </main>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/vegas.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/ReservationFormControl.js"></script>
</body>

</html>
