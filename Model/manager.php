<?php

class DonationManagement
{
    private ?int $managementId;
    private ?int $idDonation;
    private ?string $adminName;
    private ?string $recipientName;
    private ?DateTime $distributionDate;
    private ?int $quantity;
    private ?float $allocatedPercentage;
    private ?float $priceAfterPercentage;
    private ?string $note;

    public function __construct(
        ?int $managementId = null,
        ?int $idDonation = null,
        ?string $adminName = null,
        ?string $recipientName = null,
        ?DateTime $distributionDate = null,
        ?int $quantity = null,
        ?float $allocatedPercentage = null,
        ?float $priceAfterPercentage = null,
        ?string $note = null
    ) {
        $this->managementId = $managementId;
        $this->idDonation = $idDonation;
        $this->adminName = $adminName;
        $this->recipientName = $recipientName;
        $this->distributionDate = $distributionDate;
        $this->quantity = $quantity;
        $this->allocatedPercentage = $allocatedPercentage;
        $this->priceAfterPercentage = $priceAfterPercentage;
        $this->note = $note;
    }

    // Getters and Setters
    public function getManagementId(): ?int
    {
        return $this->managementId;
    }

    public function setManagementId(?int $managementId): void
    {
        $this->managementId = $managementId;
    }

    public function getIdDonation(): ?int
    {
        return $this->idDonation;
    }

    public function setIdDonation(?int $idDonation): void
    {
        $this->idDonation = $idDonation;
    }

    public function getAdminName(): ?string
    {
        return $this->adminName;
    }

    public function setAdminName(string $adminName): void
    {
        $this->adminName = $adminName;
    }

    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    public function setRecipientName(string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    public function getDistributionDate(): ?DateTime
    {
        return $this->distributionDate;
    }

    public function setDistributionDate(DateTime $distributionDate): void
    {
        $this->distributionDate = $distributionDate;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getAllocatedPercentage(): ?float
    {
        return $this->allocatedPercentage;
    }

    public function setAllocatedPercentage(?float $allocatedPercentage): void
    {
        $this->allocatedPercentage = $allocatedPercentage;
    }

    public function getPriceAfterPercentage(): ?float
    {
        return $this->priceAfterPercentage;
    }

    public function setPriceAfterPercentage(float $priceAfterPercentage): void
    {
        $this->priceAfterPercentage = $priceAfterPercentage;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    // Method to calculate price after the allocated percentage
    public function calculatePriceAfterPercentage(): void
    {
        if ($this->quantity && $this->allocatedPercentage !== null) {
            $this->priceAfterPercentage = $this->quantity * (1 - ($this->allocatedPercentage / 100));
        } else {
            $this->priceAfterPercentage = 0.00;
        }
    }
}
?>
