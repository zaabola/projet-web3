<?php
require_once '../controller/donation_controller.php';
require_once '../model/donations.php';

$host = 'localhost';
$db = 'emprunt';
$user = 'root';
$pass = '';

try {
    // Initialize PDO for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize DonationController
    $controller = new DonationController($pdo);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $donationId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($donationId > 0) {
            // Call the delete method from DonationController
            $controller->deleteDonation($donationId);

            // Redirect back with success message
            header('Location: donations_page.php?success=Donation deleted successfully');
            exit();
        }
    }
} catch (PDOException $e) {
    die("<div class='error-message'>Database error: " . $e->getMessage() . "</div>");
}
?>
<?php
require_once '../controller/donation_controller.php';
require_once '../model/donations.php';

$host = 'localhost';
$db = 'emprunt';
$user = 'root';
$pass = '';

$donationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$donation = null;

try {
    // Initialize PDO for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize DonationController
    $controller = new DonationController($pdo);

    // If an ID is provided, fetch the donation data
    if ($donationId > 0) {
        $donation = $controller->getDonationById($donationId);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $donorName = isset($_POST['donor_name']) ? $_POST['donor_name'] : '';
        $donorEmail = isset($_POST['donor_email']) ? $_POST['donor_email'] : '';
        $donationAmount = isset($_POST['donation_amount']) ? (float)$_POST['donation_amount'] : 0.0;
        $message = isset($_POST['message']) ? $_POST['message'] : '';

        if ($donationId > 0) {
            // Update the donation
            $controller->updateDonation($donationId, $donorName, $donorEmail, $donationAmount, $message);
            header('Location: donations_page.php?success=Donation updated successfully');
            exit();
        } else {
            // Insert a new donation if no ID is given
            $controller->addDonation($donorName, $donorEmail, $donationAmount, $message);
            header('Location: donations_page.php?success=Donation added successfully');
            exit();
        }
    }
} catch (PDOException $e) {
    die("<div class='error-message'>Database error: " . $e->getMessage() . "</div>");
}
?>

<!-- HTML Form for Adding/Editing Donation -->
<section class="content">
    <div class="container-fluid">
        <h3 class="text-center"><?= $donationId > 0 ? 'Edit Donation' : 'Add New Donation' ?></h3>

        <form method="post">
            <div class="form-group">
                <label for="donor_name">Donor Name</label>
                <input type="text" name="donor_name" id="donor_name" class="form-control" value="<?= htmlspecialchars($donation ? $donation['donor_name'] : '') ?>" required>
            </div>
            <div class="form-group">
                <label for="donor_email">Donor Email</label>
                <input type="email" name="donor_email" id="donor_email" class="form-control" value="<?= htmlspecialchars($donation ? $donation['donor_email'] : '') ?>" required>
            </div>
            <div class="form-group">
                <label for="donation_amount">Donation Amount</label>
                <input type="number" step="0.01" name="donation_amount" id="donation_amount" class="form-control" value="<?= htmlspecialchars($donation ? $donation['donation_amount'] : '') ?>" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control"><?= htmlspecialchars($donation ? $donation['message'] : '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= $donationId > 0 ? 'Update Donation' : 'Add Donation' ?></button>
        </form>
    </div>
</section>
