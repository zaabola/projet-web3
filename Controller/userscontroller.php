<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/users.php');

class userscontroller
{
  public function listUsers()
  {
    $sql = "SELECT * FROM users";
    $db = Config::getConnexion();
    try {
      $liste = $db->query($sql);
      return $liste;
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

  function deleteUser($id)
  {
    $sql = "DELETE FROM users WHERE id = :id";
    $db = Config::getConnexion();
    $req = $db->prepare($sql);
    $req->bindValue(':id', $id);

    try {
      $req->execute();
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

  function addUser($user)
  {
    $sql = "INSERT INTO users (nom, prenom, type, mdp, email) VALUES (:nom, :prenom, :type, :mdp, :email)";
    $db = Config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        'nom' => $user->getNom(),
        'prenom' => $user->getPrenom(),

        'type' => $user->getType(),
        'mdp' => $user->getMdp(),
        'email' => $user->getEmail()
      ]);
      error_log("User added successfully with email: " . $user->getEmail());
    } catch (Exception $e) {
      error_log("Error adding user: " . $e->getMessage());
      throw $e; // Re-throw to handle in calling code
    }
  }
  function updateUser($user, $id)
  {
    try {
      $db = Config::getConnexion();

      $query = $db->prepare(
        'UPDATE users SET 
                    nom = :nom,
                    prenom = :prenom,
                    type = :type,
                    mdp = :mdp,
                    email = :email
                WHERE id = :id'
      );

      $query->execute([
        'id' => $id,
        'nom' => $user->getNom(),
        'prenom' => $user->getPrenom(),
        'type' => $user->getType(),
        'mdp' => $user->getMdp(),
        'email' => $user->getEmail()
      ]);

      echo $query->rowCount() . " records UPDATED successfully <br>";
      return true;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function authentifier($email, $mdp)
  {
    $sql = "SELECT * FROM users WHERE email = ? AND mdp = ?";
    $db = Config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([$email, $mdp]);

      $user = $query->fetch();
      error_log("Résultat authentification : " . print_r($user, true));

      if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['mdp'] = $user['mdp'];
        $_SESSION['type'] = $user['type'];

        error_log("Session après authentification : " . print_r($_SESSION, true));
      }
      return $user;
    } catch (Exception $e) {
      error_log("Erreur d'authentification : " . $e->getMessage());
      return false;
    }
  }

  public function getUserByEmail($email)
  {
    $db = Config::getConnexion();
    $sql = "SELECT * FROM users WHERE email = :email";
    $query = $db->prepare($sql);
    $query->bindValue(':email', $email);

    try {
      $query->execute();
      return $query->fetch();
    } catch (Exception $e) {
      error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
      return false;
    }
  }

  public function saveResetCode($userId, $resetCode)
  {
    $db = Config::getConnexion();
    $sql = "UPDATE users SET reset_code = :reset_code WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(':reset_code', $resetCode);
    $query->bindValue(':id', $userId);

    try {
      $query->execute();
    } catch (Exception $e) {
      error_log("Erreur lors de l'enregistrement du code de réinitialisation : " . $e->getMessage());
    }
  }

  public function verifyResetCode($resetCode)
  {
    $db = Config::getConnexion();
    $sql = "SELECT * FROM users WHERE reset_code = :reset_code";
    $query = $db->prepare($sql);
    $query->bindValue(':reset_code', $resetCode);

    try {
      $query->execute();
      return $query->fetch();
    } catch (Exception $e) {
      error_log("Erreur lors de la vérification du code de réinitialisation : " . $e->getMessage());
      return false;
    }
  }

  public function updatePassword($userId, $newPassword)
  {
    $db = Config::getConnexion();
    $sql = "UPDATE users SET mdp = :mdp, reset_code = NULL WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(':mdp', $newPassword);
    $query->bindValue(':id', $userId);

    try {
      $query->execute();
    } catch (Exception $e) {
      error_log("Erreur lors de la mise à jour du mot de passe : " . $e->getMessage());
    }
  }
}
