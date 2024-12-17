<?php
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/tooplate-barista.css" rel="stylesheet">  
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: url('../images/vue_mosqué_tunis-1-scaled.jpg') no-repeat center center fixed; 
      background-size: cover;
      color: #fff;
    }
    .form-container-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px; /* Space between the form and image */
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 400px;
    }
    .form-container h2 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #333;
      text-align: center;
    }
    .form-container p {
      text-align: center;
      margin-bottom: 20px;
      color: #666;
    }
    .form-row {
      display: flex;
      gap: 15px;
      margin-bottom: 15px;
    }
    input, select, button {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 4px rgba(0, 123, 255, 0.2);
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .error-message {
      color: red;
      font-size: 12px;
      margin-top: -10px;
      margin-bottom: 10px;
    }
    .card-number-container {
    position: relative;
    margin-bottom: 15px;
}

.card-number-container input {
    padding-right: 50px; /* Leave space for the logo */
}

.card-logo {
    width: 40px;
    height: auto;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: top 0.2s ease-in-out; /* Smooth adjustment */
}

.card-number-container .error-message {
    position: absolute;
    top: 100%; /* Place the error message below the input field */
    left: 0;
    color: red;
    font-size: 12px;
    margin-top: 5px;
}

    .form-container-wrapper {
  display: flex;
  align-items: stretch; /* Ensures the form and image have the same height */
  justify-content: center;
  gap: 20px;
}

.form-container {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 400px;
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* Ensures the form content is spaced out */
}

.image-container {
  flex-shrink: 0;
  height: 100%; /* Ensures the image fills the height of its container */
}

.side-image {
  width: 550px;
  height: 100%; /* Set to 100% height to match the form container */
  object-fit: cover; /* Ensures the image fits proportionally within the container */
  border-radius: 5px;
}

  </style>
</head>
<body class="donation-page">
<main>
<nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="index.html">
                            <img src="../images/logo.png" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
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
                                <li>
                                    <a href="index1.php#section_69" class="nav-link click-scroll">Shop</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="#section_5">Reclamation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link click-scroll" href="panier.php">Panier</a>
                                </li>
                            </ul>

                            <button id="lang-switch" class="btn btn-outline-primary me-2" >Switch Language</button>

                        </div>
                    
                        <div class="d-flex ms-3">
                         <a href="reservation.php" class="btn btn-outline-primary me-2">Reservation</a>
                         <a href="donation.php" class="btn btn-outline-primary me-2">Donation</a>
                        </div>
                    </div>
                </nav>

    <section>
      <div class="form-container-wrapper">
        <!-- Form Container -->
        <div class="form-container">
          <h2>Make a Donation</h2>
          <p>Your contribution will make a big difference.</p>
          <form method="POST" action="">
            <div class="form-row">
              <input type="text" name="firstName" placeholder="First Name" value="<?php echo htmlspecialchars($_POST['firstName'] ?? ''); ?>">
              <span class="error-message"><?php echo $errors["firstName"] ?? ""; ?></span>
              <input type="text" name="lastName" placeholder="Last Name" value="<?php echo htmlspecialchars($_POST['lastName'] ?? ''); ?>">
              <span class="error-message"><?php echo $errors["lastName"] ?? ""; ?></span>
            </div>
            <div class="form-row card-number-container">
    <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 1234 1234 1234"
        value="<?php echo htmlspecialchars($_POST['cardNumber'] ?? ''); ?>" oninput="updateCardLogo(); updateCardLogoPosition();">
    <img id="cardLogo" src=" ../FrontOffice/images/other.jpeg" alt="Card Logo" class="card-logo">
    <span class="error-message"><?php echo $errors["cardNumber"] ?? ""; ?></span>
</div>

            <div class="form-row">
              <select name="expirationMonth">
                <option value="">Month</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <?php echo ($_POST['expirationMonth'] ?? '') === str_pad($i, 2, "0", STR_PAD_LEFT) ? "selected" : ""; ?>>
                    <?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>
                  </option>
                <?php endfor; ?>
              </select>
              <select name="expirationYear">
                <option value="">Year</option>
                <?php for ($i = date("Y"); $i <= date("Y") + 20; $i++): ?>
                  <option value="<?php echo $i; ?>" <?php echo ($_POST['expirationYear'] ?? '') == $i ? "selected" : ""; ?>>
                    <?php echo $i; ?>
                  </option>
                <?php endfor; ?>
              </select>
              <span class="error-message"><?php echo $errors["expiration"] ?? ""; ?></span>
            </div>
            <div class="form-row">
              <input type="text" name="cvc" placeholder="CVC" value="<?php echo htmlspecialchars($_POST['cvc'] ?? ''); ?>">
              <span class="error-message"><?php echo $errors["cvc"] ?? ""; ?></span>
            </div>
            <div class="form-row">
              <select name="country">
                <option value="">Select Country</option>
                <?php
                $countries = [
                  "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria",
                  "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia",
                  "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia",
                  "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)",
                  "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
                  "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France",
                  "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti",
                  "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Italy", "Jamaica", "Japan",
                  "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea (North)", "Korea (South)", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia",
                  "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives",
                  "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro",
                  "Morocco", "Mozambique", "Myanmar (Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria",
                  "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines",
                  "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines",
                  "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia",
                  "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland",
                  "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
                  "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan",
                  "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                ];

                foreach ($countries as $countryOption) {
                  $selected = (isset($_POST["country"]) && $_POST["country"] === $countryOption) ? "selected" : "";
                  echo "<option value=\"$countryOption\" $selected>$countryOption</option>";
                }
                ?>
              </select>
              <span class="error-message"><?php echo $errors["country"] ?? ""; ?></span>
            </div>
            <!-- Read-only donation amount field -->
            <div class="form-row">
              <input type="text" name="donationAmount" class="readonly-input" value="<?php echo htmlspecialchars($_POST['amount'] ?? ''); ?>" readonly>
              <span class="error-message"><?php echo $errors["amount"] ?? ""; ?></span>
            </div>
            <button type="submit">Donate Now</button>
          </form>
          <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
          <?php endif; ?>
        </div>

        <!-- Image Container -->
        <div class="image-container">
          <img src="../images/don.webp" alt="Image Description" class="side-image">
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
    cardLogo.src = '/view/FrontOffice/images/' + defaultLogo;
    return;  
  }

  var logo = getCardLogo(cardNumber);

  if (logo) {
    cardLogo.src = '/view/FrontOffice/images/' + logo;
  } else {
    cardLogo.src = '/view/FrontOffice/images/' + defaultLogo;
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
function updateCardLogoPosition() {
    const cardNumberInput = document.querySelector('#cardNumber');
    const cardLogo = document.querySelector('#cardLogo');
    const errorMessage = cardNumberInput.nextElementSibling; // Assuming the error message is the next element

    if (errorMessage && errorMessage.textContent.trim() !== "") {
        cardLogo.style.top = '30%'; // Move up if the error is shown
    } else {
        cardLogo.style.top = '50%'; // Reset to center if no error
    }
}

document.querySelector('#cardNumber').addEventListener('input', updateCardLogo);
document.querySelector('#cardNumber').addEventListener('input', updateCardLogoPosition);


  </script>
</body>
</html>