    <?php
    class reservation{
        private ?int $id;
        private ?string $nom;
        private ?string $prenom;
        private ?string $mail;
        private ?string $tel;
        private ?string $destination;
        private ?string $commentaire;
        private ?DateTime $date;
        private ?int $matricule;//foreign key from the table bus

        public function __construct($nom, $prenom, $mail, $tel, $destination,$commentaire,$date,$matricule){
            $this->nom = $nom;
            $this ->prenom = $prenom;
            $this ->mail = $mail;
            $this ->tel = $tel;
            $this ->destination = $destination;
            $this ->commentaire = $commentaire;
            $this ->date = $date;
            $this->matricule = $matricule;
        }

        public function getId() : int{
            return $this->id;
        }

        public function setId($id) : void{
            $this ->id = $id;
        }

        public function getNom() : string{
            return $this->nom;
        }

        public function setNom($nom) : void{
            $this ->nom = $nom;
        }

        public function getPrenom() : string{
            return $this->prenom;
        }

        public function setPrenom($prenom) : void{
            $this ->prenom = $prenom;
        }

        public function getMail() : string{
            return $this->mail;
        }

        public function setMail($mail) : void{
            $this ->mail = $mail;
        }

        public function getTel() : ?string {
            return $this->tel;
        }
        
        public function setTel(?string $tel) : void {
            $this->tel = $tel;
        }
        

        public function getDestination() : string{
            return $this->destination;
        }

        public function setDestination($destination) : void{
            $this ->destination = $destination;
        }

        public function getCommentaire() : string{
            return $this->commentaire;
        }

        public function setCommentaire($commentaire) : void{
            $this ->departement = $commentaire;
        }

        public  function getDate() : DateTime{
            return $this->date;
        }
        public function setDate(DateTime $date) : void{
            $this->date = $date;
        }
    
        public function getMatricule () : int{
            return $this->matricule;
        }
        public function setMatricule ($matricule) : void{
            $this->matricule = $matricule;
        }
    }
    ?>