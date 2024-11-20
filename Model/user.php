<?php
class User {

    private ?int $id = null;
    private ?string $nom = null;
    private ?string $prenom = null;   // Nouvelle propriété
    private ?string $email = null;
    private ?string $mdp = null;
    private ?string $role = null;
    private ?string $adresse = null;  // Nouvelle propriété
    private ?string $telephone = null; // Nouvelle propriété

    // Constructeur
    public function __construct($id, $nom, $prenom, $email, $mdp, $role, $adresse, $telephone) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
    }

    // Méthodes getter
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;   // Getter pour prenom
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAdresse() {
        return $this->adresse;  // Getter pour adresse
    }

    public function getTelephone() {
        return $this->telephone; // Getter pour telephone
    }

    // Méthodes setter
    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;   // Setter pour prenom
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMotDePasse($mdp) {
        $this->mdp = $mdp;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;  // Setter pour adresse
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone; // Setter pour telephone
    }
}
?>
