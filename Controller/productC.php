<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/commande.php');

class productC
{
  public function create($productx)
  {
    $sql = "INSERT INTO `commande`(`nom`, `prenom`, `numero`, `addresse` , `email`) VALUES (:nom, :prenom, :numero, :adresse , :email)";
    $db = config::getConnexion();
    try {
      $req = $db->prepare($sql);
      $req->execute([
        'nom' => $productx->getNom(),
        'prenom' => $productx->getPrenom(), // Corrected variable name
        'numero' => $productx->getNumTel(), // Corrected variable name
        'adresse' => $productx->getAdresse(), // Corrected variable name
        'email' => $productx->getEmail() // Corrected variable name
      ]);
      header('Location: mainpage.php');
      exit(); // Stop further execution
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function read()
  {
    $sql = "SELECT * FROM productx";
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
    $sql = "DELETE FROM `productx` WHERE `ID_Commande` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $ID_Commande, // Corrected parameter name
      ]);
      header('Location: tables.php');
      exit(); // Stop further execution
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function update($ID_Commande, $etat)
  {
    $sql = "UPDATE `volontaire` SET `etat` = :etat WHERE `ID_Commande` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $ID_Commande, // Corrected parameter name
        'etat' => $etat,
      ]);
      header('Location: tables.php');
      exit(); // Stop further execution
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }
}
