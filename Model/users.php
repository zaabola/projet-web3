<?php

class users {
    private $id;
    private  $nom;
    private  $prenom;
    private  $type;
    private  $classe;
    private  $mdp;
    private  $email;
    private  $reset_code;

    // Constructor
    public function __construct( $id=null,  $nom=null,  $prenom=null,  $type=null,  $classe=null,  $mdp=null,  $email=null,$reset_code=null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->type = $type;
        $this->classe = $classe;
        $this->mdp = $mdp;
        $this->email = $email;
        $this->reset_code=$reset_code;
    }

    // Getters and Setters

    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getNom() {
        return $this->nom;
    }
    
    public function setNom($nom) {
        $this->nom = $nom;
    }
    
    public function getPrenom() {
        return $this->prenom;
    }
    
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function getClasse() {
        return $this->classe;
    }
    
    public function setClasse($classe) {
        $this->classe = $classe;
    }
    
    public function getMdp() {
        return $this->mdp;
    }
    
    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
}

?>
