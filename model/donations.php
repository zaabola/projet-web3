<?php
class Donation
{
    private ?int $idDonation;
    private ?string $donorName;
    private ?string $donorEmail;
    private ?float $donationAmount;
    private ?DateTime $donationDate;
    private ?string $message;

    // Constructeur
    public function __construct(int $idDonation, string $donorName, string $donorEmail, float $donationAmount, DateTime $donationDate, string $message)
    {
        $this->idDonation = $idDonation;
        $this->donorName = $donorName;
        $this->donorEmail = $donorEmail;
        $this->donationAmount = $donationAmount;
        $this->donationDate = $donationDate;
        $this->message = $message;
    }

    // Getters et Setters
    public function getIdDonation(): int
    {
        return $this->idDonation;
    }

    public function setIdDonation(int $idDonation): void
    {
        $this->idDonation = $idDonation;
    }

    public function getDonorName(): string
    {
        return $this->donorName;
    }

    public function setDonorName(string $donorName): void
    {
        $this->donorName = $donorName;
    }

    public function getDonorEmail(): string
    {
        return $this->donorEmail;
    }

    public function setDonorEmail(string $donorEmail): void
    {
        $this->donorEmail = $donorEmail;
    }

    public function getDonationAmount(): float
    {
        return $this->donationAmount;
    }

    public function setDonationAmount(float $donationAmount): void
    {
        $this->donationAmount = $donationAmount;
    }

    public function getDonationDate(): DateTime
    {
        return $this->donationDate;
    }

    public function setDonationDate(DateTime $donationDate): void
    {
        $this->donationDate = $donationDate;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
