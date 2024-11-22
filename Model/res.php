    <?php
    class reservevation{
        private ?int $id;
        private ?string $nom;
        private ?string $prenom;
        private ?string $mail;
        private ?int $tel;
        private ?string $destination;
        private ?string $commentaire;
        private ?DateTime $date;

        public function __construct($nom, $prenom, $mail, $tel, $destination,$commentaire,$date){
            $this->nom = $nom;
            $this ->prenom = $prenom;
            $this ->mail = $mail;
            $this ->tel = $tel;
            $this ->destination = $destination;
            $this ->commentaire = $commentaire;
            $this ->date = $date;
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

        public function getTel() : int{
            return $this->tel;
        }

        public function setTel($tel) : void{
            $this ->tel = $tel;
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

        public function setDepartement($commentaire) : void{
            $this ->departement = $commentaire;
        }

        public  function getDate() : DateTime{
            return $this->date;
        }
        public function setDate(DateTime $date) : void{
            $this->date = $date;
        }
    }
    ?>