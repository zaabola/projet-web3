<?php
class Volontaire
{
    private $id = null;
    private $nom = null;
    private $prenom = null;
    private $numero = null;
    private $exp = null;
    private $etat = 0; 
    private $email=null;

    public function __construct($nom, $prenom, $numero, $exp, $email)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numero = $numero;
        $this->exp = $exp;
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($Prenom)
    {
        $this->prenom = $Prenom;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($Numero)
    {
        $this->numero = $Numero;
    }

    public function getExp()
    {
        return $this->exp;
    }

    public function setExp($exp)
    {
        $this->exp = $exp;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }


    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }
    
}