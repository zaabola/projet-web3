<?php
//include(__DIR__ . '/../config.php');
class DonationManagementController
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

  public function listDonationManagement()
  {
    $stmt = $this->db->query("SELECT * FROM donation_management");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getDonationDetailsById($idDonation)
  {
    $stmt = $this->db->prepare("
            SELECT donor_name, donation_amount 
            FROM donation 
            WHERE id_donation = :id_donation
        ");
    $stmt->bindParam(':id_donation', $idDonation, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function addDonationManagement($idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage = null)
  {
    $stmt = $this->db->prepare("
            INSERT INTO donation_management (id_donation, admin_name, donor_name, distribution_date, donation_amount, allocated_percentage) 
            VALUES (:id_donation, :admin_name, :recipient_name, :distribution_date, :quantity, :allocated_percentage)
        ");
    $stmt->execute([
      ':id_donation' => $idDonation,
      ':admin_name' => $adminName,
      ':recipient_name' => $recipientName,
      ':distribution_date' => $distributionDate,
      ':quantity' => $quantity,
      ':allocated_percentage' => $allocatedPercentage,
    ]);
  }

  // Add this method to fetch a specific donation management by its ID
  public function getDonationManagementById($managementId)
  {
    $stmt = $this->db->prepare("
            SELECT * 
            FROM donation_management 
            WHERE management_id = :management_id
        ");
    $stmt->bindParam(':management_id', $managementId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updateDonationManagement($managementId, $idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage = null)
  {
    $stmt = $this->db->prepare("
            UPDATE donation_management 
            SET 
                id_donation = :id_donation,
                admin_name = :admin_name,
                donor_name = :recipient_name,
                distribution_date = :distribution_date,
                donation_amount = :quantity,
                allocated_percentage = :allocated_percentage
            WHERE management_id = :management_id
        ");
    $stmt->execute([
      ':management_id' => $managementId,
      ':id_donation' => $idDonation,
      ':admin_name' => $adminName,
      ':recipient_name' => $recipientName,
      ':distribution_date' => $distributionDate,
      ':quantity' => $quantity,
      ':allocated_percentage' => $allocatedPercentage,
    ]);
  }

  public function deleteDonationManagement($managementId)
  {
    $stmt = $this->db->prepare("DELETE FROM donation_management WHERE management_id = :management_id");
    $stmt->bindParam(':management_id', $managementId, PDO::PARAM_INT);
    $stmt->execute();
  }
}
