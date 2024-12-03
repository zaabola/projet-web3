<?php
include_once '../../config.php';
include_once '../../Model/user.php';

class userC
{
    // Méthode pour ajouter un utilisateur avec les nouveaux attributs
    public function addUser(User $user) {
        try {
            $pdo = config::getConnexion();
    
            // Mise à jour de la requête pour inclure la nationalité
            $sql = "INSERT INTO user (nom, prenom, email, mdp, role, adresse, telephone, nationalite) 
                    VALUES (?, ?, ?, ?, 0, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
    
            // Ajout de la nationalité comme dernier paramètre
            $stmt->execute([
                $user->getNom(),
                $user->getPrenom(),
                $user->getEmail(),
                $user->getMdp(),
                $user->getAdresse(),
                $user->getTelephone(),
                $user->getNationalite() // Récupération de la nationalité
            ]);
    
            $_SESSION['user_added'] = true;
    
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    // Méthode pour supprimer un utilisateur
    public function deleteUser($userId) {
        try {
            $sql = "DELETE FROM user WHERE id = :id";
            $stmt = config::getConnexion()->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
          
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Méthode pour mettre à jour un utilisateur, incluant les nouveaux champs
        public function updateUser($userId, User $user) {
            try {
                $sql = "UPDATE user SET nom = :nom, prenom = :prenom, email = :email, mdp = :mdp, role = :role, adresse = :adresse, telephone = :telephone , nationalite= :nationalite  WHERE id = :id";
                $stmt = config::getConnexion()->prepare($sql);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->bindValue(':nom', $user->getNom(), PDO::PARAM_STR);
                $stmt->bindValue(':prenom', $user->getPrenom(), PDO::PARAM_STR);
                $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
                $stmt->bindValue(':mdp', $user->getMdp(), PDO::PARAM_STR);
                $stmt->bindValue(':role', $user->getRole(), PDO::PARAM_INT);  // Assurez-vous que $user->getRole() renvoie un entier
                $stmt->bindValue(':adresse', $user->getAdresse(), PDO::PARAM_STR);
                $stmt->bindValue(':telephone', $user->getTelephone(), PDO::PARAM_STR);
                $stmt->bindValue(':nationalite', $user->getNationalite(), PDO::PARAM_STR);
                
                $stmt->execute();   
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        }

    // Méthode pour afficher tous les utilisateurs
    public function listUsers() {
        try {
            $sql = "SELECT * FROM user";
            $stmt = config::getConnexion()->query($sql);
            $users = [];
            if ($stmt) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $user = array(
                        'id' => $row['id'],
                        'nom' => $row['nom'],
                        'prenom' => $row['prenom'],  // Ajout de prenom
                        'email' => $row['email'],
                        'mdp' => $row['mdp'],
                        'role' => $row['role'],
                        'adresse' => $row['adresse'],  // Ajout d'adresse
                        'telephone' => $row['telephone']  // Ajout de telephone
                    );
                    $users[] = $user;
                }
            }
            return $users;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Méthode pour récupérer un utilisateur par son identifiant
    public function listUsersById($userId) {
        try {
            $sql = "SELECT * FROM user WHERE id = :id";
            $stmt = config::getConnexion()->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                return new User(
                    $row['id'],
                    $row['nom'],
                    $row['prenom'],  // Récupération du prenom
                    $row['email'],
                    $row['mdp'],
                    $row['role'],
                    $row['adresse'],  // Récupération de l'adresse
                    $row['telephone'] ,
                    nationalite: $row['nationalite']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    // Méthode pour rechercher des utilisateurs par leur nom
    public function searchUsers($keyword) {
        try {
            $pdo = config::getConnexion();
            $sql = "SELECT * FROM user WHERE nom LIKE :keyword";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
            $stmt->execute();
    
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User(
                    $row['id'],
                    $row['nom'],
                    $row['prenom'],  // Ajout de prenom
                    $row['email'],
                    $row['mdp'],
                    $row['role'],
                    $row['adresse'],  // Ajout d'adresse
                    $row['telephone'] , // Ajout de telephone
                    nationalite: $row['nationalite']
                );
    
                $users[] = $user;
            }
    
            return $users;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public function getStatisticsByNationality() {
        try {
            $pdo = config::getConnexion();
    
            // Récupérer le nombre d'utilisateurs par nationalité
            $sql = "SELECT nationalite, COUNT(*) as count FROM user GROUP BY nationalite";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function recoverPassword($email) {
        try {
            $conn = config::getConnexion();

            // Préparer la requête pour récupérer le mot de passe
            $stmt = $conn->prepare("SELECT mdp FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Vérifier si l'utilisateur existe
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                return $user["mdp"]; // Retourner le mot de passe si trouvé
            } else {
                return false; // Si aucun utilisateur n'est trouvé
            }

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


}

?>
