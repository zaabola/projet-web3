<?php
include(__DIR__ . '/../config.php');

class DonationControllerWithManagement
{
    // Liste les donations avec les informations de gestion
    public function listDonationsWithManagement()
    {
        $sql = "
            SELECT 
                donation.id_donation,
                donation.donor_name,
                donation.donor_email,
                donation.donation_amount,
                donation.donation_date,
                donation.message,
                donation_management.management_id,
                donation_management.admin_name,
                donation_management.recipient_name,
                donation_management.distribution_date,
                donation_management.quantity,
                donation_management.allocated_percentage,
                donation_management.price_after_percentage
            FROM 
                donation
            JOIN 
                donation_management
            ON 
                donation.id_donation = donation_management.id_donation
        ";

        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Ajouter une nouvelle donation avec gestion
    public function addDonationWithManagement($name, $email, $amount, $message, $adminName, $recipientName, $distributionDate, $quantity, $allocatedPercentage = null)
    {
        try {
            // Ajouter la donation
            $db = config::getConnexion();
            $query = $db->prepare("INSERT INTO donation (donor_name, donor_email, donation_amount, message, donation_date) 
                VALUES (:donor_name, :donor_email, :donation_amount, :message, NOW())");
            $query->execute([
                'donor_name' => $name,
                'donor_email' => $email,
                'donation_amount' => $amount,
                'message' => $message,
            ]);

            // Récupérer l'ID de la dernière donation insérée
            $idDonation = $db->lastInsertId();

            // Ajouter la gestion de donation
            $priceAfterPercentage = $this->calculatePriceAfterPercentage($quantity, $allocatedPercentage);
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

    // Supprimer une donation avec gestion
    public function deleteDonationWithManagement($id)
    {
        try {
            // Supprimer la gestion de donation
            $db = config::getConnexion();
            $query = $db->prepare("DELETE FROM donation_management WHERE id_donation = :id");
            $query->execute(['id' => $id]);

            // Supprimer la donation
            $query = $db->prepare("DELETE FROM donation WHERE id_donation = :id");
            $query->execute(['id' => $id]);

        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Calculer le prix après pourcentage
    private function calculatePriceAfterPercentage($quantity, $allocatedPercentage)
    {
        if ($quantity && $allocatedPercentage !== null) {
            return $quantity * (1 - ($allocatedPercentage / 100));
        }
        return 0.00;  // Retourne 0 si la quantité ou le pourcentage n'est pas fourni
    }
}
?>
