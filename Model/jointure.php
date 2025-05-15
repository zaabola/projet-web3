<?php

class DonationManagementJoin
{
    private $db;
    private ?Donation $donation;
    private ?DonationManagement $donationManagement;

    public function __construct($db, Donation $donation = null, DonationManagement $donationManagement = null)
    {
        $this->db = $db;
        $this->donation = $donation;
        $this->donationManagement = $donationManagement;
    }

    // Méthode pour récupérer une donation avec sa gestion associée
    public function getDonationWithManagement($donationId)
    {
        // Récupérer les informations de donation
        $sqlDonation = "SELECT * FROM donation WHERE id_donation = :donationId";
        $queryDonation = $this->db->prepare($sqlDonation);
        $queryDonation->execute(['donationId' => $donationId]);
        $donationData = $queryDonation->fetch(PDO::FETCH_ASSOC);

        if ($donationData) {
            // Initialiser l'objet Donation avec les données récupérées
            $this->donation = new Donation($this->db);
            $this->donation->setIdDonation($donationData['id_donation']);
            $this->donation->setDonorName($donationData['donor_name']);
            $this->donation->setDonorEmail($donationData['donor_email']);
            $this->donation->setDonationAmount($donationData['donation_amount']);
            $this->donation->setDonationDate(new DateTime($donationData['donation_date']));
            $this->donation->setMessage($donationData['message']);
        }

        // Récupérer les informations de gestion des donations
        $sqlManagement = "SELECT * FROM donation_management WHERE id_donation = :donationId";
        $queryManagement = $this->db->prepare($sqlManagement);
        $queryManagement->execute(['donationId' => $donationId]);
        $managementData = $queryManagement->fetch(PDO::FETCH_ASSOC);

        if ($managementData) {
            // Initialiser l'objet DonationManagement avec les données récupérées
            $this->donationManagement = new DonationManagement($this->db);
            $this->donationManagement->setManagementId($managementData['management_id']);
            $this->donationManagement->setIdDonation($managementData['id_donation']);
            $this->donationManagement->setAdminName($managementData['admin_name']);
            $this->donationManagement->setRecipientName($managementData['recipient_name']);
            $this->donationManagement->setDistributionDate(new DateTime($managementData['distribution_date']));
            $this->donationManagement->setQuantity($managementData['quantity']);
            $this->donationManagement->setAllocatedPercentage($managementData['allocated_percentage']);
            $this->donationManagement->setPriceAfterPercentage($managementData['price_after_percentage']);
        }
    }

    // Méthode pour récupérer une liste de donations avec leurs gestions
    public function getAllDonationsWithManagement()
    {
        $sql = "
            SELECT d.id_donation, d.donor_name, d.donor_email, d.donation_amount, d.donation_date, d.message,
                   dm.management_id, dm.admin_name, dm.recipient_name, dm.distribution_date, dm.quantity,
                   dm.allocated_percentage, dm.price_after_percentage
            FROM donation d
            LEFT JOIN donation_management dm ON d.id_donation = dm.id_donation
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Créer une liste de résultats avec les objets Donation et DonationManagement
        $donationManagements = [];
        foreach ($result as $row) {
            $donation = new Donation($this->db);
            $donation->setIdDonation($row['id_donation']);
            $donation->setDonorName($row['donor_name']);
            $donation->setDonorEmail($row['donor_email']);
            $donation->setDonationAmount($row['donation_amount']);
            $donation->setDonationDate(new DateTime($row['donation_date']));
            $donation->setMessage($row['message']);

            $management = new DonationManagement($this->db);
            $management->setManagementId($row['management_id']);
            $management->setIdDonation($row['id_donation']);
            $management->setAdminName($row['admin_name']);
            $management->setRecipientName($row['recipient_name']);
            $management->setDistributionDate(new DateTime($row['distribution_date']));
            $management->setQuantity($row['quantity']);
            $management->setAllocatedPercentage($row['allocated_percentage']);
            $management->setPriceAfterPercentage($row['price_after_percentage']);

            $donationManagements[] = [
                'donation' => $donation,
                'management' => $management
            ];
        }

        return $donationManagements;
    }
}
?>
