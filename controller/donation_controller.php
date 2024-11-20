<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../model/donations.php');

class DonationController
{
    // List all donations
    public function listDonations() {
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

    // Add a donation
    public function addDonation($donation) {
        $sql = "INSERT INTO donation (donor_name, donor_email, donation_amount, donation_date, message) 
                VALUES (:donor_name, :donor_email, :donation_amount, :donation_date, :message)";
        
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute([
                'donor_name' => $donation->getDonorName(),
                'donor_email' => $donation->getDonorEmail(),
                'donation_amount' => $donation->getDonationAmount(),
                'donation_date' => $donation->getDonationDate(),
                'message' => $donation->getMessage(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Delete a donation
    public function deleteDonation($id) {
        $sql = "DELETE FROM donation WHERE id_donation = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Get a donation by ID
    public function getDonationById($id) {
        $sql = "SELECT * FROM donation WHERE id_donation = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);

            $donation = $query->fetch();
            return $donation;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update a donation
    public function updateDonation($donation, $id) {
        $sql = "UPDATE donation SET 
                donor_name = :donor_name,
                donor_email = :donor_email,
                donation_amount = :donation_amount,
                donation_date = :donation_date,
                message = :message
            WHERE id_donation = :id";
        
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'donor_name' => $donation->getDonorName(),
                'donor_email' => $donation->getDonorEmail(),
                'donation_amount' => $donation->getDonationAmount(),
                'donation_date' => $donation->getDonationDate(),
                'message' => $donation->getMessage(),
            ]);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
