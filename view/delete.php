<?php
require_once 'controller/DonationController.php';

if (isset($_GET['id'])) {
    $controller = new DonationController();
    $controller->deleteDonation($_GET['id']);
    header("Location: index.php?success=Donation deleted successfully");
}
?>
