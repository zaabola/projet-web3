<?php
class Bus {
    private ?int $matricule; // Primary key in the 'bus' table.
    private ?string $nomChauffeur; // Maps to 'nom_chauffeur'.
    private ?string $depart; // Use string for 'TIME' type in PHP.
    private ?int $nbrPlace; // Maps to 'nbr_place'.
    private ?string $destination; // Maps to 'destination' (varchar).
    private ?int $idReservation; // Foreign key to 'id_reservation' in 'reservation' table.

    // Constructor
    public function __construct($matricule, $nomChauffeur, $depart, $nbrPlace, $destination, $idReservation) {
        $this->matricule = $matricule;
        $this->nomChauffeur = $nomChauffeur;
        $this->depart = $depart;
        $this->nbrPlace = $nbrPlace;
        $this->destination = $destination;
        $this->idReservation = $idReservation;
    }

    // Getters and setters
    public function getMatricule(): int {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): void {
        $this->matricule = $matricule;
    }

    public function getNomChauffeur(): string {
        return $this->nomChauffeur;
    }

    public function setNomChauffeur(string $nomChauffeur): void {
        $this->nomChauffeur = $nomChauffeur;
    }

    public function getDepart(): string {
        return $this->depart;
    }

    public function setDepart(string $depart): void {
        $this->depart = $depart;
    }

    public function getNbrPlace(): int {
        return $this->nbrPlace;
    }

    public function setNbrPlace(int $nbrPlace): void {
        $this->nbrPlace = $nbrPlace;
    }

    public function getDestination(): string {
        return $this->destination;
    }

    public function setDestination(string $destination): void {
        $this->destination = $destination;
    }

    public function getIdReservation(): int {
        return $this->idReservation;
    }

    public function setIdReservation(int $idReservation): void {
        $this->idReservation = $idReservation;
    }
}
?>
