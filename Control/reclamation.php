<?php
require_once('c:/xampp/htdocs/projet/config.php');

class ReclamationC {
    public function create($reclamation) {
        $sql = "INSERT INTO `reclamation` (`Id_commande`, `Commentaire`, `Nom`, `Prenom`, `Email`, `Tel`) VALUES (:Id_commande, :Commentaire, :Nom, :Prenom, :Email, :Tel)";
        $db = config::getConnexion();
        try {
            $req = $db->prepare($sql);
            $req->execute([
                'Id_commande' => $reclamation->getIdCommande(),
                'Commentaire' => $reclamation->getCommentaire(),
                'Nom' => $reclamation->getNom(),
                'Prenom' => $reclamation->getPrenom(),
                'Email' => $reclamation->getEmail(),
                'Tel' => $reclamation->getTel()
            ]);
            header('Location: mainpage.php');
            exit();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    public function read() {
        $sql = "SELECT * FROM reclamation";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function delete($Id_reclamation) {
        $sql = "DELETE FROM `reclamation` WHERE `Id_reclamation` = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $Id_reclamation
            ]);
            header('Location: tables.php');
            exit();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    public function update($Id_reclamation, $reclamation) {
        $sql = "UPDATE `reclamation` SET `Id_commande` = :Id_commande, `Commentaire` = :Commentaire, `Nom` = :Nom, `Prenom` = :Prenom, `Email` = :Email, `Tel` = :Tel WHERE `Id_reclamation` = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $Id_reclamation,
                'Id_commande' => $reclamation->getIdCommande(),
                'Commentaire' => $reclamation->getCommentaire(),
                'Nom' => $reclamation->getNom(),
                'Prenom' => $reclamation->getPrenom(),
                'Email' => $reclamation->getEmail(),
                'Tel' => $reclamation->getTel()
            ]);
            header('Location: tables.php');
            exit();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
}
?>
