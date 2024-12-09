<?php 
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";

// Database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$errors = [];
$successMessage = ""; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $firstName = trim($_POST["firstName"]);
  $lastName = trim($_POST["lastName"]);
  $cardNumber = trim($_POST["cardNumber"]);
  $expirationMonth = $_POST["expirationMonth"];
  $expirationYear = $_POST["expirationYear"];
  $cvc = trim($_POST["cvc"]);
  $country = $_POST["country"];

  if (empty($firstName)) $errors["firstName"] = "First Name is required.";
  if (empty($lastName)) $errors["lastName"] = "Last Name is required.";
  if (!preg_match('/^\d{16}$/', $cardNumber)) $errors["cardNumber"] = "Card number must be 16 digits.";
  if (empty($expirationMonth) || empty($expirationYear)) $errors["expiration"] = "Expiration date is required.";
  if (!preg_match('/^\d{3,4}$/', $cvc)) $errors["cvc"] = "CVC must be 3 digits.";
  if (empty($country)) $errors["country"] = "Country is required.";

  if (empty($errors)) {
    $successMessage = "Donation submitted successfully!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Form</title>
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
    .card-logo {
      width: 40px;
      height: auto;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
    }
    .card-number-container {
      position: relative;
    }
    .card-number-container input {
      padding-right: 50px; /* Add space for the logo */
    }
  </style>
</head>
<body>
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
        <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 1234 1234 1234" value="<?php echo htmlspecialchars($_POST['cardNumber'] ?? ''); ?>" oninput="updateCardLogo()">
        <img id="cardLogo" src="../images/other.jpeg" alt="Card Logo" class="card-logo">
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
            "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru",
            "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
            "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles",
            "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan",
            "South Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
            "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom",
            "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
          ];
          foreach ($countries as $country) {
            $selected = ($_POST['country'] ?? '') === $country ? 'selected' : '';
            echo "<option value=\"$country\" $selected>$country</option>";
          }
          ?>
        </select>
      </div>
      <button type="submit">Donate Now</button>
      <?php if ($successMessage): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
      <?php endif; ?>
    </form>
  </div>

  <script>
function updateCardLogo() {
  var cardNumber = document.getElementById('cardNumber').value.replace(/\D/g, ''); 
  var cardLogo = document.getElementById('cardLogo');

  var defaultLogo = 'other.jpeg';  

  if (cardNumber === '') {
    cardLogo.src = '/web/view/images/' + defaultLogo;
    return;  
  }

  var logo = getCardLogo(cardNumber);

  if (logo) {
    cardLogo.src = '/web/view/images/' + logo;
  } else {
    cardLogo.src = '/web/view/images/' + defaultLogo;
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
