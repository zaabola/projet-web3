<?php 
class Reclamation {
    private $Id_reclamation = null;
    private $Id_commande = null;
    private $Commentaire = null;
    private $Nom = null;
    private $Prenom = null;
    private $Email = null;
    private $Tel = null;

    public function __construct($Id_commande, $Commentaire, $Nom, $Prenom, $Email, $Tel) {
        $this->Id_commande = $Id_commande;
        $this->Commentaire = $Commentaire;
        $this->Nom = $Nom;
        $this->Prenom = $Prenom;
        $this->Email = $Email;
        $this->Tel = $Tel;
    }
    
    public function getID() {
        return $this->Id_reclamation;
    }

    public function getIdCommande() {
        return $this->Id_commande;
    }

    public function setIdCommande($Id_commande) {
        $this->Id_commande = $Id_commande;
    }

    public function getCommentaire() {
        return $this->Commentaire;
    }

    public function setCommentaire($Commentaire) {
        $this->Commentaire = $Commentaire;
    }

    public function getNom() {
        return $this->Nom;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    public function getPrenom() {
        return $this->Prenom;
    }

    public function setPrenom($Prenom) {
        $this->Prenom = $Prenom;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function getTel() {
        return $this->Tel;
    }

    public function setTel($Tel) {
        $this->Tel = $Tel;
    }
}
?>
