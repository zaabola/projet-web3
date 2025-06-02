<?php
include(__DIR__ . '/../config.php');

class DonationController
{
  public function listDonations()
  {
    $sql = "SELECT id_donation, donor_name, donor_email, donation_amount, donation_date, message FROM donation";

    try {
      $db = config::getConnexion();
      $query = $db->prepare($sql);
      $query->execute();
      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  public function addDonation($name, $email, $amount, $message)
  {
    $sql = "INSERT INTO donation (donor_name, donor_email, donation_amount, message, donation_date) 
                VALUES (:donor_name, :donor_email, :donation_amount, :message, NOW())";

    try {
      $db = config::getConnexion();
      $query = $db->prepare($sql);
      $query->execute([
        'donor_name' => $name,
        'donor_email' => $email,
        'donation_amount' => $amount,
        'message' => $message,
      ]);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  public function deleteDonation($id)
  {
    $sql = "DELETE FROM donation WHERE id_donation = :id";

    try {
      $db = config::getConnexion();
      $query = $db->prepare($sql);
      $query->execute(['id' => $id]);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  public function getDonationById($id)
  {
    $sql = "SELECT * FROM donation WHERE id_donation = :id";

    try {
      $db = config::getConnexion();
      $query = $db->prepare($sql);
      $query->execute(['id' => $id]);
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  public function updateDonation($id, $name, $email, $amount, $message)
  {
    $sql = "UPDATE donation SET 
                donor_name = :donor_name,
                donor_email = :donor_email,
                donation_amount = :donation_amount,
                message = :message
            WHERE id_donation = :id";

    try {
      $db = config::getConnexion();
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $id,
        'donor_name' => $name,
        'donor_email' => $email,
        'donation_amount' => $amount,
        'message' => $message,
      ]);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }
}
