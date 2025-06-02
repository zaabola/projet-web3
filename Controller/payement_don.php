<?php
class DonationController
{
  private $db;

  public function __construct()
  {
    try {
      $this->db = new PDO('mysql:host=localhost;dbname=emprunt', 'root', '');
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  // Add a new donation to the payement_don table
  public function addDonation($firstName, $lastName, $cardNumber, $expirationMonth, $expirationYear, $cvc, $country)
  {
    try {
      $stmt = $this->db->prepare("
                INSERT INTO payement_don (first_name, last_name, card_number, expiration_month, expiration_year, cvc, country) 
                VALUES (:first_name, :last_name, :card_number, :expiration_month, :expiration_year, :cvc, :country)
            ");
      $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':card_number' => $cardNumber,
        ':expiration_month' => $expirationMonth,
        ':expiration_year' => $expirationYear,
        ':cvc' => $cvc,
        ':country' => $country,
      ]);
      return "Donation added successfully!";
    } catch (PDOException $e) {
      return "Failed to add donation: " . $e->getMessage();
    }
  }

  // List all donations from the payement_don table
  public function listDonations()
  {
    $stmt = $this->db->query("SELECT * FROM payement_don ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get a donation by ID from the payement_don table
  public function getDonationById($donationId)
  {
    $stmt = $this->db->prepare("SELECT * FROM payement_don WHERE id = :id");
    $stmt->bindParam(':id', $donationId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a donation in the payement_don table
  public function updateDonation($id, $firstName, $lastName, $cardNumber, $expirationMonth, $expirationYear, $cvc, $country)
  {
    $stmt = $this->db->prepare("
            UPDATE payement_don 
            SET 
                first_name = :first_name, 
                last_name = :last_name, 
                card_number = :card_number, 
                expiration_month = :expiration_month, 
                expiration_year = :expiration_year, 
                cvc = :cvc, 
                country = :country 
            WHERE id = :id
        ");
    $stmt->execute([
      ':id' => $id,
      ':first_name' => $firstName,
      ':last_name' => $lastName,
      ':card_number' => $cardNumber,
      ':expiration_month' => $expirationMonth,
      ':expiration_year' => $expirationYear,
      ':cvc' => $cvc,
      ':country' => $country,
    ]);
  }

  // Delete a donation from the payement_don table
  public function deleteDonation($donationId)
  {
    $stmt = $this->db->prepare("DELETE FROM payement_don WHERE id = :id");
    $stmt->bindParam(':id', $donationId, PDO::PARAM_INT);
    $stmt->execute();
  }
}
