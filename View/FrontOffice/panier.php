<?php
session_start();
require_once('tcpdf/tcpdf.php'); // Include the TCPDF library

$host = "localhost";
$username = "root";
$password = "";
$dbname = "empreinte1";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle cart actions and form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    // Ensure id_produit exists in the POST data
    if (isset($_POST['id_produit'])) {
        $id_produit = intval($_POST['id_produit']);

        switch ($action) {
            case 'increase_quantity':
                $_SESSION['cart'][$id_produit] += 1;
                break;
            case 'decrease_quantity':
                if ($_SESSION['cart'][$id_produit] > 1) {
                    $_SESSION['cart'][$id_produit] -= 1;
                } else {
                    unset($_SESSION['cart'][$id_produit]);
                }
                break;
            case 'remove_product':
                unset($_SESSION['cart'][$id_produit]);
                break;
        }
    }

    // Handle payment submission
    if ($action == 'submit_payment') {
        $Adresse_Client = $_POST['Adresse_Client'];
        $Tel_Client = $_POST['Tel_Client'];
        $Nom_Client = $_POST['Nom_Client'];
        $Prenom_Client = $_POST['Prenom_Client'];

        // Calculate total cost for the panier
        $total_cost = 0;
        $product_list = [];
        foreach ($_SESSION['cart'] as $id_produit => $quantity) {
            $result = $conn->query("SELECT Nom_Produit, Prix, Qte FROM produit WHERE Id_produit = $id_produit");
            $product = $result->fetch_assoc();
            $total_cost += $product['Prix'] * $quantity;

            // Add product details to the list
            $product_list[] = [
                'name' => $product['Nom_Produit'],
                'price' => $product['Prix'],
                'quantity' => $quantity,
                'total' => $product['Prix'] * $quantity
            ];

            // Update product quantity
            $new_quantity = $product['Qte'] - $quantity;
            $update_quantity_sql = "UPDATE produit SET Qte = $new_quantity WHERE Id_produit = $id_produit";
            if ($conn->query($update_quantity_sql) !== TRUE) {
                die("Error updating quantity: " . $conn->error);
            }
        }

        // Insert a new record into panier_items with the total cost
        $insert_panier_sql = "INSERT INTO panier_items (total_cost) VALUES ($total_cost)";
        if ($conn->query($insert_panier_sql) === TRUE) {
            // Get the last inserted id_panier
            $id_panier = $conn->insert_id;
        } else {
            die("Error inserting panier items: " . $conn->error);
        }

        // Insert data into commande table
        $insert_commande_sql = "INSERT INTO commande (Adresse_client, Tel_client, Nom_client, Prenom_client, id_panier) VALUES ('$Adresse_Client', '$Tel_Client', '$Nom_Client', '$Prenom_Client', '$id_panier')";
        if ($conn->query($insert_commande_sql) === TRUE) {
            echo "Payment submitted successfully!";
            // Clear the cart
            $_SESSION['cart'] = array();
        } else {
            die("Error inserting commande: " . $conn->error);
        }
    }

    // Handle receipt generation
    if ($action == 'generate_receipt') {
        $Adresse_Client = $_POST['Adresse_Client'];
        $Tel_Client = $_POST['Tel_Client'];
        $Nom_Client = $_POST['Nom_Client'];
        $Prenom_Client = $_POST['Prenom_Client'];
        $total_cost = $_POST['total_cost'];

        // Generate PDF receipt
        generatePDFReceipt($Adresse_Client, $Tel_Client, $Nom_Client, $Prenom_Client, $total_cost);
    }
}

// Function to generate PDF receipt
function generatePDFReceipt($Adresse_Client, $Tel_Client, $Nom_Client, $Prenom_Client, $total_cost) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company');
    $pdf->SetTitle('Receipt');
    $pdf->SetSubject('Receipt');

    // Add a page
    $pdf->AddPage();

    // Set some content
    $html = "
    <h1>Receipt</h1>
    <p><strong>Client Name:</strong> {$Nom_Client} {$Prenom_Client}</p>
    <p><strong>Address:</strong> {$Adresse_Client}</p>
    <p><strong>Phone:</strong> {$Tel_Client}</p>
    <p><strong>Total Cost:</strong> {$total_cost} DT</p>
    <p>We received your order. We will make sure that it will reach you shortly.</p>
    ";

    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // Close and output PDF document
    $pdf->Output('receipt.pdf', 'D'); // 'D' for download
}

// Fetch cart items
$total_cost = 0;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$cart_items = $_SESSION['cart'];
$products = [];

if (count($cart_items) > 0) {
    $ids = implode(',', array_keys($cart_items));

    $sql = "
    SELECT 
        p.Id_produit,
        p.Nom_Produit,
                p.Prix
    FROM 
        produit p
    WHERE 
        p.Id_produit IN ($ids)";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --white-color:                  #ffffff;
            --primary-color:                #BC6C25;
            --secondary-color:              #DDA15E;
            --section-bg-color:             #b78752;
            --custom-btn-bg-color:          #BC6C25;
            --custom-btn-bg-hover-color:    #DDA15E;
            --dark-color:                   #000000;
            --p-color:                      #717275;
            --border-color:                 #7fffd4;
            --link-hover-color:             #E76F51;

            --body-font-family:             'Plus Jakarta Sans', sans-serif;

            --h1-font-size:                 68px;
            --h2-font-size:                 46px;
            --h3-font-size:                 32px;
            --h4-font-size:                 28px;
            --h5-font-size:                 24px;
            --h6-font-size:                 22px;
            --p-font-size:                  20px;
            --btn-font-size:                16px;
            --form-btn-font-size:           18px;
            --menu-font-size:               16px;

            --border-radius-large:          100px;
            --border-radius-medium:         20px;
            --border-radius-small:          10px;

            --font-weight-thin:             200;
            --font-weight-light:            300;
            --font-weight-normal:           400;
            --font-weight-bold:             700;
        }

        body {
            background-color: var(--section-bg-color);
            font-family: var(--body-font-family); 
        }

        h2 {
            font-size: var(--h2-font-size);
            color: var(--white-color);
        }

        p.empty-cart {
            color: var(--white-color);
            font-size: var(--h2-font-size);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid var(--border-color);
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: var(--primary-color);
            color: var(--white-color);
        }

        table tr:nth-child(even) {
            background-color: var(--custom-btn-bg-hover-color);
        }

        table tr:hover {
            background-color: var(--secondary-color);
        }

        .btn {
            font-size: var(--btn-font-size);
            margin: 5px;
            color: var(--white-color);
            background-color: var(--custom-btn-bg-color);
            border: none;
            border-radius: var(--border-radius-small);
            padding: 10px 20px;
        }

        .btn-primary {
            background-color: var(--custom-btn-bg-color);
        }

        .btn-success {
            background-color: var(--secondary-color);
        }

        .btn-danger {
            background-color: var(--link-hover-color);
        }

        .btn:hover {
            background-color: var(--custom-btn-bg-hover-color);
        }

        .form-group label {
            font-weight: var(--font-weight-bold);
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-small);
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (count($products) > 0) : ?>
        <h2>Cart Items</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product) : ?>
                <?php 
                    $id_produit = $product['Id_produit']; 
                    $nom_produit = $product['Nom_Produit']; 
                    $prix = $product['Prix']; 
                    $quantity = $cart_items[$id_produit];
                    $total = $prix * $quantity;
                    $total_cost += $total;
                ?>
                <tr>
                    <td><?php echo $nom_produit; ?></td>
                    <td><?php echo $prix; ?> DT</td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $total; ?> DT</td>
                    <td>
                        <form method="POST" action="panier.php" style="display:inline;">
                            <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">
                            <input type="hidden" name="action" value="increase_quantity">
                            <button type="submit" class="btn btn-success">+</button>
                        </form>
                        <form method="POST" action="panier.php" style="display:inline;">
                            <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">
                            <input type="hidden" name="action" value="decrease_quantity">
                            <button type="submit" class="btn btn-warning">-</button>
                        </form>
                        <form method="POST" action="panier.php" style="display:inline;">
                            <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">
                            <input type="hidden" name="action" value="remove_product">
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Total Cost</td>
                <td><?php echo $total_cost; ?> DT</td>
                <td></td>
            </tr>
        </table>
    <?php else : ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php endif; ?>

    <!-- Buttons -->
    <div style="margin-top: 20px;">
        <a href="index.php" class="btn btn-primary">Back to Main Page</a>
        <button onclick="showPaymentForm()" class="btn btn-success">Pay</button>
    </div>

    <div id="paymentForm" style="display: none; margin-top: 20px;">
        <form method="POST" action="panier.php" onsubmit="return validateForm()">
            <input type="hidden" name="action" value="submit_payment">

            <div class="form-group">
                <label for="Adresse_Client">Address</label>
                <span id="addressError" style="color: red;"></span>
                <input type="text" class="form-control" id="Adresse_Client" name="Adresse_Client">
            </div>

            <div class="form-group">
                <label for="Tel_Client">Phone</label>
                <span id="phoneError" style="color: red;"></span>
                <input type="text" class="form-control" id="Tel_Client" name="Tel_Client">
            </div>

            <div class="form-group">
                <label for="Nom_Client">First Name</label>
                <span id="firstNameError" style="color: red;"></span>
                <input type="text" class="form-control" id="Nom_Client" name="Nom_Client">
            </div>

            <div class="form-group">
                <label for="Prenom_Client">Last Name</label>
                <span id="lastNameError" style="color: red;"></span>
                <input type="text" class="form-control" id="Prenom_Client" name="Prenom_Client">
            </div>

            <button type="submit" class="btn btn-primary">Submit Payment</button>
            <button type="button" class="btn btn-secondary" onclick="generateReceipt()">Receipt</button>
        </form>
    </div>

    <script>
        function showPaymentForm() {
            document.getElementById('paymentForm').style.display = 'block';
        }

        function validateForm() {
            var isValid = true;

            var address = document.getElementById('Adresse_Client').value;
            var phone = document.getElementById('Tel_Client').value;
            var firstName = document.getElementById('Nom_Client').value;
            var lastName = document.getElementById('Prenom_Client').value;

            // Clear previous error messages
            document.getElementById('addressError').innerText = '';
            document.getElementById('phoneError').innerText = '';
            document.getElementById('firstNameError').innerText = '';
            document.getElementById('lastNameError').innerText = '';

            // Validate Address
            if (address === '') {
                document.getElementById('addressError').innerText = 'Address is required.';
                isValid = false;
            }

            // Validate Phone
            if (phone === '') {
                document.getElementById('phoneError').innerText = 'Phone is required.';
                isValid = false;
            }

            // Validate First Name
            if (firstName === '') {
                document.getElementById('firstNameError').innerText = 'First Name is required.';
                isValid = false;
            }

            // Validate Last Name
            if (lastName === '') {
                document.getElementById('lastNameError').innerText = 'Last Name is required.';
                isValid = false;
            }

            // Additional validation can be added here

            return isValid;
        }

        function generateReceipt() {
            var address = document.getElementById('Adresse_Client').value;
            var phone = document.getElementById('Tel_Client').value;
            var firstName = document.getElementById('Nom_Client').value;
            var lastName = document.getElementById('Prenom_Client').value;

            // Create a form and submit it to generate receipt
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'panier.php';

            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'generate_receipt';
            form.appendChild(actionInput);

            var addressInput = document.createElement('input');
            addressInput.type = 'hidden';
            addressInput.name = 'Adresse_Client';
            addressInput.value = address;
            form.appendChild(addressInput);

            var phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'Tel_Client';
            phoneInput.value = phone;
            form.appendChild(phoneInput);

            var firstNameInput = document.createElement('input');
            firstNameInput.type = 'hidden';
            firstNameInput.name = 'Nom_Client';
            firstNameInput.value = firstName;
            form.appendChild(firstNameInput);

            var lastNameInput = document.createElement('input');
            lastNameInput.type = 'hidden';
            lastNameInput.name = 'Prenom_Client';
            lastNameInput.value = lastName;
            form.appendChild(lastNameInput);

            var productListInput = document.createElement('input');
            productListInput.type = 'hidden';
            productListInput.name = 'product_list';
            productListInput.value = JSON.stringify(<?php echo json_encode($products); ?>);
            form.appendChild(productListInput);

            var totalCostInput = document.createElement('input');
            totalCostInput.type = 'hidden';
            totalCostInput.name = 'total_cost';
            totalCostInput.value = '<?php echo $total_cost; ?>';
            form.appendChild(totalCostInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</div>
</body>
</html>
