<?php
require_once('C:/xampp/htdocs/web/config.php');
include 'C:/xampp/htdocs/web/model/volntaire.php';

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
        $sql = "SELECT * FROM volontaire";
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


    public function update($id,$etat)
    {
        $sql = "UPDATE `volontaire` SET `etat`= :etat  WHERE `id` =:id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'etat' => $etat,

            ]);
            header('Location:tables.php');
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
}
?>