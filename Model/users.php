<?php

class users
{
  private  $id;
  private  $nom;
  private  $prenom;
  private  $type;
  private  $mdp;
  private  $email;
  private  $reset_code;

  // Constructor
  public function __construct($id = null,  $nom = null,  $prenom = null,  $type = null,  $mdp = null,  $email = null, $reset_code = null)
  {
    $this->id = $id;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->type = $type;
    $this->mdp = $mdp;
    $this->email = $email;
    $this->reset_code = $reset_code;
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

  public function setPrenom($prenom)
  {
    $this->prenom = $prenom;
  }

  public function getType()
  {
    return $this->type;
  }

  public function setType($type)
  {
    $this->type = $type;
  }

  public function getMdp()
  {
    return $this->mdp;
  }

  public function setMdp($mdp)
  {
    $this->mdp = $mdp;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getResetCode()
  {
    return $this->reset_code;
  }
  public function setResetCode($code)
  {
    $this->reset_code = $code;
  }
}
