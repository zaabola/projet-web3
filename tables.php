<?php
require_once 'Controller/donation_controller.php';

$controller = new DonationController();
$donations = $controller->listDonations();

?>

<!DOCTYPE html>
<html>

<head>
  <title>Donation List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h3 class="text-center">Donation List</h3>
    <a href="update.php" class="btn btn-success mb-3">Add New Donation</a>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Donor Name</th>
          <th>Email</th>
          <th>Amount</th>
          <th>Message</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($donations)): ?>
          <?php foreach ($donations as $donation): ?>
            <tr>
              <td><?= htmlspecialchars($donation['id_donation']) ?></td>
              <td><?= htmlspecialchars($donation['donor_name']) ?></td>
              <td><?= htmlspecialchars($donation['donor_email']) ?></td>
              <td>$<?= htmlspecialchars(number_format($donation['donation_amount'], 2)) ?></td>
              <td><?= htmlspecialchars($donation['message']) ?></td>
              <td>
                <a href="update.php?id=<?= $donation['id_donation'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?= $donation['id_donation'] ?>" class="btn btn-danger btn-sm"
                  onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">No donations found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>
