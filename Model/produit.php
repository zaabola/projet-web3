<?php 
class Produit {
    private $Id_produit = null;
    private $Nom_Produit = null;
    private $Qte = null;
    private $Prix = null;
    private $Image = null;

    public function __construct($Nom_Produit, $Qte, $Prix, $Image) {
        $this->Nom_Produit = $Nom_Produit;
        $this->Qte = $Qte;
        $this->Prix = $Prix;
        $this->Image = $Image;
    }
    
    public function getID() {
        return $this->Id_produit;
    }

    public function getNomProduit() {
        return $this->Nom_Produit;
    }

    public function setNomProduit($Nom_Produit) {
        $this->Nom_Produit = $Nom_Produit;
    }

    public function getQte() {
        return $this->Qte;
    }

    public function setQte($Qte) {
        $this->Qte = $Qte;
    }

    public function getPrix() {
        return $this->Prix;
    }

    public function setPrix($Prix) {
        $this->Prix = $Prix;
    }

    public function getImage() {
        return $this->Image;
    }

    public function setImage($Image) {
        $this->Image = $Image;
    }
}
?>
