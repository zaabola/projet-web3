<?php 
class commande {
    private ?int $Id_Commande ;
    private ?int $ID_Produit;
    private ?string $Adresse_Client ;
    private ?int $Num_tel_Client;
    private ?string $Nom_Client ;
    private ?string $Prenom_Client;

    public function __construct($Adresse_Client, $Num_tel_Client, $Nom_Client, $Prenom_Client) {
        $this->Adresse_Client = $Adresse_Client; // Corrected property name
        $this->Num_tel_Client = $Num_tel_Client;
        $this->Nom_Client = $Nom_Client;
        $this->Prenom_Client = $Prenom_Client;
    }
    
    public function getID() {
        return $this->Id_Commande; // Return only one ID
    }
    public function setID($Id_Commande) {
         $this->Id_Commande; // Return only one ID
    }
    public function getNom() {
        return $this->Nom_Client;
    }

    public function setNom($Nom_Client) { // Added parameter
        $this->Nom_Client = $Nom_Client;
    }

    public function getPrenom() {
        return $this->Prenom_Client;
    }

    public function setPrenom($Prenom_Client) { // Added parameter
        $this->Prenom_Client = $Prenom_Client;
    }

    public function getAdresse() {
        return $this->Adresse_Client;
    }

    public function setAdresse($Adresse_Client) { // Added parameter
        $this->Adresse_Client = $Adresse_Client;
    }

    public function getNumTel() {
        return $this->Num_tel_Client;
    }

    public function setNumTel($Num_tel_Client) { // Added parameter
        $this->Num_tel_Client = $Num_tel_Client;
    }
}
?>