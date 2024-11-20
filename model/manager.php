<?php

class DonationManagement
{
    private int $managementId;
    private int $idDonation;
    private string $adminName;
    private string $recipientName;
    private DateTime $distributionDate;
    private int $quantity;

    // Constructeur
    public function __construct(int $managementId, int $idDonation, string $adminName, string $recipientName, DateTime $distributionDate, int $quantity)
    {
        $this->managementId = $managementId;
        $this->idDonation = $idDonation;
        $this->adminName = $adminName;
        $this->recipientName = $recipientName;
        $this->distributionDate = $distributionDate;
        $this->quantity = $quantity;
    }

    // Getters et Setters
    public function getManagementId(): int
    {
        return $this->managementId;
    }

    public function setManagementId(int $managementId): void
    {
        $this->managementId = $managementId;
    }

    public function getIdDonation(): int
    {
        return $this->idDonation;
    }

    public function setIdDonation(int $idDonation): void
    {
        $this->idDonation = $idDonation;
    }

    public function getAdminName(): string
    {
        return $this->adminName;
    }

    public function setAdminName(string $adminName): void
    {
        $this->adminName = $adminName;
    }

    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function setRecipientName(string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    public function getDistributionDate(): DateTime
    {
        return $this->distributionDate;
    }

    public function setDistributionDate(DateTime $distributionDate): void
    {
        $this->distributionDate = $distributionDate;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
