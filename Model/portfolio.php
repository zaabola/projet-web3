<?php
class Portfolio
{
    private $id_portfolio = null;
    private $id_volontaire = null;
    private $nom = null;
    private $prenom = null;
    private $photo = null;
    private $langue = null;
    private $specialite = null;
    private $biographie = null;

    public function __construct($nom, $prenom, $photo, $langue, $specialite, $biographie)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->photo = $photo;
        $this->langue = $langue;
        $this->specialite = $specialite;
        $this->biographie = $biographie;
    }

    // Getter and Setter for id_portfolio
    public function getId_portfolio()
    {
        return $this->id_portfolio;
    }

    public function setId_portfolio($id_portfolio)
    {
        $this->id_portfolio = $id_portfolio;
    }

    // Getter and Setter for id_volontaire
    public function getId_volontaire()
    {
        return $this->id_volontaire;
    }

    public function setId_volontaire($id_volontaire)
    {
        $this->id_volontaire = $id_volontaire;
    }

    // Getter and Setter for nom
    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    // Getter and Setter for prenom
    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    // Getter and Setter for photo
    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    // Getter and Setter for langue
    public function getLangue()
    {
        return $this->langue;
    }

    public function setLangue($langue)
    {
        $this->langue = $langue;
    }

    // Getter and Setter for specialite
    public function getSpecialite()
    {
        return $this->specialite;
    }

    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;
    }

    // Getter and Setter for biographie
    public function getBiographie()
    {
        return $this->biographie;
    }

    public function setBiographie($biographie)
    {
        $this->biographie = $biographie;
    }
}
?>
