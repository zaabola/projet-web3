<?php
require_once(__DIR__ . '/../config.php');

class PanierItemsC
{
  public function create($panier)
  {
    $sql = "INSERT INTO `panier_items` (`total_cost`) VALUES (:total_cost)";
    $db = config::getConnexion();
    try {
      $req = $db->prepare($sql);
      $req->execute([
        'total_cost' => $panier->getTotalCost()
      ]);
      header('Location: mainpage.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function read()
  {
    $sql = "SELECT * FROM panier_items";
    $db = config::getConnexion();
    try {
      $liste = $db->query($sql);
      return $liste;
    } catch (Exception $e) {
      die('Erreur: ' . $e->getMessage());
    }
  }

  public function delete($id_panier)
  {
    $sql = "DELETE FROM `panier_items` WHERE `id_panier` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $id_panier
      ]);
      header('Location: tables.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  public function update($id_panier, $panier)
  {
    $sql = "UPDATE `panier_items` SET `total_cost` = :total_cost WHERE `id_panier` = :id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $id_panier,
        'total_cost' => $panier->getTotalCost()
      ]);
      header('Location: tables.php');
      exit();
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }
}
