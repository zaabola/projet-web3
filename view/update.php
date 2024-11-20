<?php
require_once 'controller/DonationController.php';

$controller = new DonationController();
$donation = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $donation = $controller->getDonationById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $donorName = $_POST['donor_name'];
    $donorEmail = $_POST['donor_email'];
    $donationAmount = $_POST['donation_amount'];
    $message = $_POST['message'];
    $controller->updateDonation($id, $donorName, $donorEmail, $donationAmount, $message);
    header("Location: index.php?success=Donation updated successfully");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Donation</title>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center">Update Donation</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($donation['id_donation']) ?>">
            <div class="mb-3">
                <label>Donor Name</label>
                <input type="text" name="donor_name" class="form-control" value="<?= htmlspecialchars($donation['donor_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="donor_email" class="form-control" value="<?= htmlspecialchars($donation['donor_email']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Donation Amount</label>
                <input type="number" name="donation_amount" class="form-control" value="<?= htmlspecialchars($donation['donation_amount']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control"><?= htmlspecialchars($donation['message']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
