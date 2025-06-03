<?php
session_start();
require_once('session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id'])) {
  // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
  header("Location: ../FrontOffice/login.php");
  exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = ""; // Your database password

try {
  // Create a PDO instance for database connection
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling for errors
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage()); // If connection fails, show error message
}

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form values
  $firstName = $_POST['firstName'] ?? '';
  $lastName = $_POST['lastName'] ?? '';
  $cardNumber = $_POST['cardNumber'] ?? '';
  $expirationMonth = $_POST['expirationMonth'] ?? '';
  $expirationYear = $_POST['expirationYear'] ?? '';
  $cvc = $_POST['cvc'] ?? '';
  $country = $_POST['country'] ?? '';
  $donationAmount = $_POST['donationAmount'] ?? 0;

  // Form validation
  if (empty($firstName)) {
    $errors['firstName'] = 'First name is required.';
  }
  if (empty($lastName)) {
    $errors['lastName'] = 'Last name is required.';
  }
  if (empty($cardNumber)) {
    $errors['cardNumber'] = 'Card number is required.';
  }
  if (empty($expirationMonth) || empty($expirationYear)) {
    $errors['expiration'] = 'Expiration date is required.';
  }
  if (empty($cvc)) {
    $errors['cvc'] = 'CVC is required.';
  }
  if (empty($country)) {
    $errors['country'] = 'Country is required.';
  }

  // If no errors, insert data into the database
  if (empty($errors)) {
    try {
      // SQL query to insert data into 'payement_don' table
      $stmt = $pdo->prepare("
                INSERT INTO payement_don 
                (first_name, last_name, card_number, expiration_month, expiration_year, cvc, country)
                VALUES
                (:first_name, :last_name, :card_number, :expiration_month, :expiration_year, :cvc, :country)
            ");

      // Execute the prepared statement with values
      $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':card_number' => $cardNumber,
        ':expiration_month' => $expirationMonth,
        ':expiration_year' => $expirationYear,
        ':cvc' => $cvc,
        ':country' => $country,
      ]);

      $successMessage = "Donation submitted successfully and saved to the database!";
    } catch (PDOException $e) {
      $errors['database'] = "Error saving to the database: " . $e->getMessage();
      error_log($e->getMessage()); // Log error to the server log
    }
  }
}
?>
<!DOCTYPE html>
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
  <link href="../FrontOffice/css/bootstrap.min.css" rel="stylesheet">
  <link href="../FrontOffice/css/bootstrap-icons.css" rel="stylesheet">
  <link href="../FrontOffice/css/tooplate-barista.css" rel="stylesheet">
  <link rel="icon" href="logo.png">
  <style>
    body {
      background: url('./images/vue_mosqué_tunis-1-scaled.jpg');

      .card-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .card-input-group input {
        flex: 1;
      }

      .card-logo {
        height: 48px;
        /* Increase size */
        width: auto;
      }

      @media (max-width: 576px) {
        .card-input-group {
          flex-direction: column;
          align-items: stretch;
        }

        .card-logo {
          align-self: flex-end;
          margin-top: 5px;
        }
      }
    }
  </style>
</head>

<body class="donation-page">
  <main>
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
              <a class="nav-link click-scroll" href="index1.php#section_5">Complaint</a>
            </li>
            <li class="nav-item">
              <a class="nav-link click-scroll" href="donation.php">Donate</a>
            </li>
          </ul>
          <div class="ms-lg-3">
            <a class="btn custom-btn custom-border-btn" href="logout.php">Log Out<i class="bi-arrow-up-right ms-2"></i></a>
          </div>
    </nav>

    <section>
      <div class="container">
        <div class="form-container">
          <div class="col-lg-10 col-12 mx-auto">
            <div class="booking-form-wrap">
              <form class="custom-form booking-form" method="POST" action="">
                <div class="text-center mb-4 pb-lg-2">
                  <em class="text-white">Fill in the card information to complete your donation</em>
                  <h2 class="text-white">Payment</h2>
                  <?php if (!empty($successMessage)): ?>
                    <p class="text-success"><?= htmlspecialchars($successMessage); ?></p>
                  <?php elseif (!empty($errors)): ?>
                    <p class="text-danger">Please correct the errors below.</p>
                  <?php endif; ?>
                </div>

                <div class="booking-form-body">
                  <div class="row">
                    <div class="col-md-6 col-12 mb-3">
                      <input type="text" name="firstName" class="form-control" placeholder="First Name" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>">
                      <span class="text-danger small"><?= $errors["firstName"] ?? "" ?></span>
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                      <input type="text" name="lastName" class="form-control" placeholder="Last Name" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>">
                      <span class="text-danger small"><?= $errors["lastName"] ?? "" ?></span>
                    </div>

                    <div class="col-12 mb-3">
                      <div class="card-input-group">
                        <input type="text" id="cardNumber" name="cardNumber" class="form-control" placeholder="Card Number"
                          value="<?= htmlspecialchars($_POST['cardNumber'] ?? '') ?>"
                          oninput="updateCardLogo();">
                        <img id="cardLogo" src="../FrontOffice/images/other.jpeg" alt="Card Logo" class="card-logo">
                      </div>
                      <span class="text-danger small"><?= $errors["cardNumber"] ?? "" ?></span>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                      <select name="expirationMonth" class="form-control">
                        <option value="">Month</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                          <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <?= ($_POST['expirationMonth'] ?? '') === str_pad($i, 2, "0", STR_PAD_LEFT) ? "selected" : "" ?>>
                            <?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                      <select name="expirationYear" class="form-control">
                        <option value="">Year</option>
                        <?php for ($i = date("Y"); $i <= date("Y") + 20; $i++): ?>
                          <option value="<?= $i ?>" <?= ($_POST['expirationYear'] ?? '') == $i ? "selected" : "" ?>>
                            <?= $i ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                      <span class="text-danger small"><?= $errors["expiration"] ?? "" ?></span>
                    </div>

                    <div class="col-12 mb-3">
                      <input type="text" name="cvc" class="form-control" placeholder="CVC" value="<?= htmlspecialchars($_POST['cvc'] ?? '') ?>">
                      <span class="text-danger small"><?= $errors["cvc"] ?? "" ?></span>
                    </div>

                    <div class="col-12 mb-3">
                      <select name="country" class="form-control">
                        <option value="">Select your country</option>
                        <?php
                        $countries = [
                          "Afghanistan",
                          "Albania",
                          "Algeria",
                          "Andorra",
                          "Angola",
                          "Antigua and Barbuda",
                          "Argentina",
                          "Armenia",
                          "Australia",
                          "Austria",
                          "Azerbaijan",
                          "Bahamas",
                          "Bahrain",
                          "Bangladesh",
                          "Barbados",
                          "Belarus",
                          "Belgium",
                          "Belize",
                          "Benin",
                          "Bhutan",
                          "Bolivia",
                          "Bosnia and Herzegovina",
                          "Botswana",
                          "Brazil",
                          "Brunei",
                          "Bulgaria",
                          "Burkina Faso",
                          "Burundi",
                          "Cabo Verde",
                          "Cambodia",
                          "Cameroon",
                          "Canada",
                          "Central African Republic",
                          "Chad",
                          "Chile",
                          "China",
                          "Colombia",
                          "Comoros",
                          "Congo (Brazzaville)",
                          "Congo (Kinshasa)",
                          "Costa Rica",
                          "Croatia",
                          "Cuba",
                          "Cyprus",
                          "Czech Republic",
                          "Denmark",
                          "Djibouti",
                          "Dominica",
                          "Dominican Republic",
                          "Ecuador",
                          "Egypt",
                          "El Salvador",
                          "Equatorial Guinea",
                          "Eritrea",
                          "Estonia",
                          "Eswatini",
                          "Ethiopia",
                          "Fiji",
                          "Finland",
                          "France",
                          "Gabon",
                          "Gambia",
                          "Georgia",
                          "Germany",
                          "Ghana",
                          "Greece",
                          "Grenada",
                          "Guatemala",
                          "Guinea",
                          "Guinea-Bissau",
                          "Guyana",
                          "Haiti",
                          "Honduras",
                          "Hungary",
                          "Iceland",
                          "India",
                          "Indonesia",
                          "Iran",
                          "Iraq",
                          "Ireland",
                          "Israel",
                          "Italy",
                          "Jamaica",
                          "Japan",
                          "Jordan",
                          "Kazakhstan",
                          "Kenya",
                          "Kiribati",
                          "Kuwait",
                          "Kyrgyzstan",
                          "Laos",
                          "Latvia",
                          "Lebanon",
                          "Lesotho",
                          "Liberia",
                          "Libya",
                          "Liechtenstein",
                          "Lithuania",
                          "Luxembourg",
                          "Madagascar",
                          "Malawi",
                          "Malaysia",
                          "Maldives",
                          "Mali",
                          "Malta",
                          "Marshall Islands",
                          "Mauritania",
                          "Mauritius",
                          "Mexico",
                          "Micronesia",
                          "Moldova",
                          "Monaco",
                          "Mongolia",
                          "Montenegro",
                          "Morocco",
                          "Mozambique",
                          "Myanmar",
                          "Namibia",
                          "Nauru",
                          "Nepal",
                          "Netherlands",
                          "New Zealand",
                          "Nicaragua",
                          "Niger",
                          "Nigeria",
                          "North Korea",
                          "North Macedonia",
                          "Norway",
                          "Oman",
                          "Pakistan",
                          "Palau",
                          "Palestine",
                          "Panama",
                          "Papua New Guinea",
                          "Paraguay",
                          "Peru",
                          "Philippines",
                          "Poland",
                          "Portugal",
                          "Qatar",
                          "Romania",
                          "Russia",
                          "Rwanda",
                          "Saint Kitts and Nevis",
                          "Saint Lucia",
                          "Saint Vincent and the Grenadines",
                          "Samoa",
                          "San Marino",
                          "Sao Tome and Principe",
                          "Saudi Arabia",
                          "Senegal",
                          "Serbia",
                          "Seychelles",
                          "Sierra Leone",
                          "Singapore",
                          "Slovakia",
                          "Slovenia",
                          "Solomon Islands",
                          "Somalia",
                          "South Africa",
                          "South Korea",
                          "South Sudan",
                          "Spain",
                          "Sri Lanka",
                          "Sudan",
                          "Suriname",
                          "Sweden",
                          "Switzerland",
                          "Syria",
                          "Taiwan",
                          "Tajikistan",
                          "Tanzania",
                          "Thailand",
                          "Timor-Leste",
                          "Togo",
                          "Tonga",
                          "Trinidad and Tobago",
                          "Tunisia",
                          "Turkey",
                          "Turkmenistan",
                          "Tuvalu",
                          "Uganda",
                          "Ukraine",
                          "United Arab Emirates",
                          "United Kingdom",
                          "United States",
                          "Uruguay",
                          "Uzbekistan",
                          "Vanuatu",
                          "Vatican City",
                          "Venezuela",
                          "Vietnam",
                          "Yemen",
                          "Zambia",
                          "Zimbabwe"
                        ];
                        foreach ($countries as $countryOption) {
                          $selected = ($_POST["country"] ?? "") === $countryOption ? "selected" : "";
                          echo "<option value=\"$countryOption\" $selected>$countryOption</option>";
                        }
                        ?>
                      </select>
                      <span class="text-danger small"><?= $errors["country"] ?? "" ?></span>
                    </div>

                    <div class="col-12 text-center mt-4">
                      <button type="submit" class="form-control btn btn-primary">Donate Now</button>
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
  <script>
    function updateCardLogo() {
      var cardNumber = document.getElementById('cardNumber').value.replace(/\D/g, '');
      var cardLogo = document.getElementById('cardLogo');

      var defaultLogo = 'other.jpeg';

      if (cardNumber === '') {
        cardLogo.src = '../FrontOffice/images/' + defaultLogo;
        return;
      }

      var logo = getCardLogo(cardNumber);

      if (logo) {
        cardLogo.src = '../FrontOffice/images/' + logo;
      } else {
        cardLogo.src = '../FrontOffice/images/' + defaultLogo;
      }
    }

    function getCardLogo(cardNumber) {

      var visa = /^4/;
      var mastercard = /^5[1-5]/;

      if (visa.test(cardNumber)) {
        return 'visa.jpeg';
      } else if (mastercard.test(cardNumber)) {
        return 'master.jpeg';
      }

      return null;
    }
  </script>
</body>

</html>
