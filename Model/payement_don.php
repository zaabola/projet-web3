<?php
class Donation
{
    private ?int $id;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $cardNumber;
    private ?int $expirationMonth;
    private ?int $expirationYear;
    private ?string $cvc;
    private ?string $country;
    private ?DateTime $createdAt;

    public function __construct(
        ?int $id = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $cardNumber = null,
        ?int $expirationMonth = null,
        ?int $expirationYear = null,
        ?string $cvc = null,
        ?string $country = null,
        ?DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->cardNumber = $cardNumber;
        $this->expirationMonth = $expirationMonth;
        $this->expirationYear = $expirationYear;
        $this->cvc = $cvc;
        $this->country = $country;
        $this->createdAt = $createdAt ?? new DateTime();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getExpirationMonth(): ?int
    {
        return $this->expirationMonth;
    }

    public function setExpirationMonth(?int $expirationMonth): void
    {
        $this->expirationMonth = $expirationMonth;
    }

    public function getExpirationYear(): ?int
    {
        return $this->expirationYear;
    }

    public function setExpirationYear(?int $expirationYear): void
    {
        $this->expirationYear = $expirationYear;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(?string $cvc): void
    {
        $this->cvc = $cvc;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
