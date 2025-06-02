<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/portfolio.php');

class PortfolioC
{
  // Create a new Portfolio record
  public function create($portfolio)
  {
    $sql = "INSERT INTO `portfolio`(`id_volontaire`,`nom`, `prenom`, `photo`, `langue`, `specialite`, `biographie`) 
                VALUES (:id,:nom, :prenom, :photo, :langue, :specialite, :biographie)";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id' => $portfolio->getId_volontaire(),
        'nom' => $portfolio->getNom(),
        'prenom' => $portfolio->getPrenom(),
        'photo' => $portfolio->getPhoto(),
        'langue' => $portfolio->getLangue(),
        'specialite' => $portfolio->getSpecialite(),
        'biographie' => $portfolio->getBiographie(),
      ]);
      header('Location:index.php'); // Redirect after success
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  // Read all Portfolio records
  public function read()
  {
    $sql = "SELECT * FROM portfolio";
    $db = config::getConnexion();
    try {
      $list = $db->query($sql);
      return $list; // Return all portfolio entries
    } catch (Exception $e) {
      die('Erreur: ' . $e->getMessage());
    }
  }

  // Read all Portfolio records
  public function findone($id)
  {
    $sql = "SELECT * FROM portfolio WHERE id_portfolio = " . $id;
    $db = config::getConnexion();
    try {
      $list = $db->query($sql);
      return $list; // Return all portfolio entries
    } catch (Exception $e) {
      die('Erreur: ' . $e->getMessage());
    }
  }

  // Read all Portfolio records
  public function findone2($id)
  {
    $sql = "SELECT * FROM portfolio WHERE id_volontaire = " . $id;
    $db = config::getConnexion();
    try {
      $list = $db->query($sql);
      return $list; // Return all portfolio entries
    } catch (Exception $e) {
      die('Erreur: ' . $e->getMessage());
    }
  }

  // Delete a Portfolio record by ID
  public function delete($id_portfolio)
  {
    $sql = "DELETE FROM `portfolio` WHERE `id_portfolio` = :id_portfolio";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'id_portfolio' => $id_portfolio,
      ]);
      header('Location:tables.php'); // Redirect after success
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }

  // Create a new Portfolio record
  public function update($portfolio)
  {
    $sql = "UPDATE `portfolio` SET `nom`=:nom,`prenom`=:prenom,`photo`=:photo,`langue`=:langue,`specialite`=:specialite,`biographie`=:biographie WHERE `id_portfolio` = :id ";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'nom' => $portfolio->getNom(),
        'prenom' => $portfolio->getPrenom(),
        'photo' => $portfolio->getPhoto(),
        'langue' => $portfolio->getLangue(),
        'specialite' => $portfolio->getSpecialite(),
        'biographie' => $portfolio->getBiographie(),
        'id' => $portfolio->getId_portfolio(),
      ]);
      header('Location:tables.php'); // Redirect after success
    } catch (Exception $e) {
      echo 'Erreur: ' . $e->getMessage();
    }
  }
}
