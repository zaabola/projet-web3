<?php
require_once(__DIR__ . '/../config.php');

class CommandeC
{
  public function create($commande)
  {
    $sql = "INSERT INTO `commande` (`Adresse_client`, `Tel_client`, `Nom_client`, `Prenom_client`, `id_panier`) VALUES (:Adresse_client, :Tel_client, :Nom_client, :Prenom_client, :id_panier)";
    $db = config::getConnexion();
    try {
      $req = $db->prepare($sql);
      $req->execute([
        'Adresse_client' => $commande->getAdresse(),
        'Tel_client' => $commande->getTel(),
        'Nom_client' => $commande->getNom(),
        'Prenom_client' => $commande->getPrenom(),
        'id_panier' => $commande->getIdPanier()
      ]);
      header('Location: mainpage.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function read()
  {
    $sql = "SELECT * FROM commande";
    $db = config::getConnexion();
    try {
      $liste = $db->query($sql);
      return $liste;
    } catch (Exception $e) {
      die('Erreur: ' . $e->getMessage());
    }
  }

  public function delete($ID_Commande)
  {
    $sql = "DELETE FROM `commande` WHERE `Id_commande` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $ID_Commande
      ]);
      header('Location: tables.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function update($ID_Commande, $commande)
  {
    $sql = "UPDATE `commande` SET `Adresse_client` = :Adresse_client, `Tel_client` = :Tel_client, `Nom_client` = :Nom_client, `Prenom_client` = :Prenom_client, `id_panier` = :id_panier WHERE `Id_commande` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $ID_Commande,
        'Adresse_client' => $commande->getAdresse(),
        'Tel_client' => $commande->getTel(),
        'Nom_client' => $commande->getNom(),
        'Prenom_client' => $commande->getPrenom(),
        'id_panier' => $commande->getIdPanier()
      ]);
      header('Location: tables.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }
}
