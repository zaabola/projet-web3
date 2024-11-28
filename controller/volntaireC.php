<?php
require_once('C:/xampp/htdocs/web/config.php');
include 'C:/xampp/htdocs/web/model/volntaire.php';
include 'C:/xampp/htdocs/web/controller/PortfolioC.php';

class VolontaireC
{
    public function create($volontaire) 
    {
        $sql = "INSERT INTO `volontaire`(`nom`, `prenom`, `numero`, `exp`, `email`) VALUES (:nom,:prenom,:numero,:exp,:email)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $volontaire->getNom(),
                'prenom' => $volontaire->getPrenom(),
                'numero' => $volontaire->getNumero(),
                'exp' => $volontaire->getExp(),
                'email' => $volontaire->getEmail(),
            ]);
            header('Location:index.php');
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }




    public function read()
    {
        $sql = "SELECT v.*, 
                CASE 
                    WHEN p.id_portfolio IS NOT NULL THEN 1 
                    ELSE 0 
                END AS has_portfolio
            FROM volontaire v
            LEFT JOIN portfolio p ON v.id = p.id_volontaire";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur:' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `volontaire` WHERE `id` = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
            ]);
            header('Location:tables.php');
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }


    public function update($id,$etat,$email)
    {
        $sql = "UPDATE `volontaire` SET `etat`= :etat  WHERE `id` =:id";
        $db = config::getConnexion();
        $to_email = $email;
        $subject = "Mise a jour de votre demande";
        $headers = "From: Empreinte";
        if($etat==1)
        {
            $body = "Bonjour ! votre condidature a ete accepter ! veillez creer votre protfolio via ce lien http://localhost/web/view/frontoffice/CreatProtfolio.php?id=".$id;
        }else
        {
            $body = "Bonjour ! malheuresement votre condidature a ete rejete ! ";
        }
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'etat' => $etat,
            ]);
            mail($to_email, $subject, $body, $headers);
            header('Location:tables.php');
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
}
?>