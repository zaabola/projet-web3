<?php
include(__DIR__ . '/../config.php');

class DonationManagementController
{
    // List all donation management entries
    public function listDonationManagement()
    {
        $sql = "SELECT management_id, id_donation, admin_name, recipient_name, distribution_date, quantity, allocated_percentage, price_after_percentage 
                FROM donation_management";
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Add a new donation management entry
    public function addDonationManagement($idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage = null)
    {
        try {
            // Check if the donation exists
            $db = config::getConnexion();
            $query = $db->prepare("SELECT COUNT(*) FROM donation WHERE id_donation = :id_donation");
            $query->execute(['id_donation' => $idDonation]);
            $exists = $query->fetchColumn();

            if (!$exists) {
                throw new Exception("The donation with ID $idDonation does not exist. Please add it first.");
            }

            // Calculate the price after percentage
            $priceAfterPercentage = $this->calculatePriceAfterPercentage($quantity, $allocatedPercentage);

            // Proceed with the insert
            $sql = "INSERT INTO donation_management 
                    (id_donation, admin_name, recipient_name, distribution_date, quantity, allocated_percentage, price_after_percentage) 
                    VALUES (:id_donation, :admin_name, :recipient_name, :distribution_date, :quantity, :allocated_percentage, :price_after_percentage)";
            $query = $db->prepare($sql);
            $query->execute([
                'id_donation' => $idDonation,
                'admin_name' => $adminName,
                'recipient_name' => $recipientName,
                'distribution_date' => $distributionDate,
                'quantity' => $quantity,
                'allocated_percentage' => $allocatedPercentage,
                'price_after_percentage' => $priceAfterPercentage
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Delete a donation management entry
    public function deleteDonationManagement($managementId)
    {
        $sql = "DELETE FROM donation_management WHERE management_id = :management_id";
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(['management_id' => $managementId]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Get a specific donation management entry by its ID
    public function getDonationManagementById($managementId)
    {
        $sql = "SELECT * FROM donation_management WHERE management_id = :management_id";
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(['management_id' => $managementId]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Update an existing donation management entry
    public function updateDonationManagement($managementId, $idDonation, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage)
    {
        try {
            // Check if the donation exists
            $db = config::getConnexion();
            $query = $db->prepare("SELECT COUNT(*) FROM donation WHERE id_donation = :id_donation");
            $query->execute(['id_donation' => $idDonation]);
            $exists = $query->fetchColumn();

            if (!$exists) {
                throw new Exception("The donation with ID $idDonation does not exist. Please add it first.");
            }

            // Calculate the price after percentage
            $priceAfterPercentage = $this->calculatePriceAfterPercentage($quantity, $allocatedPercentage);

            // Proceed with the update
            $sql = "UPDATE donation_management SET 
                    id_donation = :id_donation,
                    admin_name = :admin_name,
                    recipient_name = :recipient_name,
                    distribution_date = :distribution_date,
                    quantity = :quantity,
                    allocated_percentage = :allocated_percentage,
                    price_after_percentage = :price_after_percentage
                WHERE management_id = :management_id";
            $query = $db->prepare($sql);
            $query->execute([
                'management_id' => $managementId,
                'id_donation' => $idDonation,
                'admin_name' => $adminName,
                'recipient_name' => $recipientName,
                'distribution_date' => $distributionDate,
                'quantity' => $quantity,
                'allocated_percentage' => $allocatedPercentage,
                'price_after_percentage' => $priceAfterPercentage
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Helper method to calculate price after percentage
    private function calculatePriceAfterPercentage($quantity, $allocatedPercentage)
    {
        if ($quantity && $allocatedPercentage !== null) {
            return $quantity * (1 - ($allocatedPercentage / 100));
        }
        return 0.00;  // Return 0 if quantity or allocatedPercentage is not provided
    }
}
?>
