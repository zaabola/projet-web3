<?php
require_once(__DIR__ . '/../Config.php');
require_once(__DIR__ . '/../Model/users.php');

class userscontroller
{
    public function listOffre()
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

    function deleteOffer($id)
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

    function addOffer($offer)
    {   
        $sql = "INSERT INTO users (nom, prenom, type, classe, mdp, email) VALUES (:nom, :prenom, :type, :classe, :mdp, :email)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $offer->getNom(),
                'prenom' => $offer->getPrenom(),
                'type' => $offer->getType(), 
                'classe' => $offer->getClasse(),
                'mdp' => $offer->getMdp(),
                'email' => $offer->getEmail()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updateOffer($offer, $id)
    {
        try {
            $db = Config::getConnexion();

            $query = $db->prepare(
                'UPDATE users SET 
                    nom = :nom,
                    prenom = :prenom,
                    type = :type,
                    classe = :classe,
                    mdp = :mdp,
                    email = :email
                WHERE id = :id'
            );

            $query->execute([
                'id' => $id,
                'nom' => $offer->getNom(),
                'prenom' => $offer->getPrenom(),
                'type' => $offer->getType(),
                'classe' => $offer->getClasse(),
                'mdp' => $offer->getMdp(),
                'email' => $offer->getEmail()
            ]);

            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); 
            return false;
        }
    }

    function showOffer($id)
    {
        $sql = "SELECT * from users where id = $id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();

            $offer = $query->fetch();
            return $offer;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
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
                // Assurons-nous que toutes les données sont stockées dans la session
                $_SESSION['id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['mdp'] = $user['mdp'];
                $_SESSION['type'] = $user['type'];
                $_SESSION['classe'] = $user['classe'];
                
                error_log("Session après authentification : " . print_r($_SESSION, true));
            }
            return $user;
        } catch (Exception $e) {
            error_log("Erreur d'authentification : " . $e->getMessage());
            return false;
        }
    }

    public function updateUserAccount($id, $nom, $prenom, $email, $mdp)
    {
        try {
            $db = Config::getConnexion();
            error_log("Tentative de mise à jour pour ID: $id");
            
            // Vérifier si l'utilisateur existe
            $checkUser = $db->prepare("SELECT * FROM users WHERE id = ?");
            $checkUser->execute([$id]);
            
            if ($checkUser->rowCount() > 0) {
                $sql = "UPDATE users 
                        SET nom = :nom,
                            prenom = :prenom,
                            email = :email,
                            mdp = :mdp
                        WHERE id = :id";

                $query = $db->prepare($sql);
                
                $params = [
                    'id' => $id,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'mdp' => $mdp
                ];
                
                error_log("Paramètres de mise à jour : " . print_r($params, true));
                
                $result = $query->execute($params);
                
                if ($result) {
                    error_log("Mise à jour réussie");
                    // Mettre à jour la session
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['email'] = $email;
                    $_SESSION['mdp'] = $mdp;
                    return true;
                } else {
                    error_log("Échec de la mise à jour - Erreur SQL : " . print_r($query->errorInfo(), true));
                }
            } else {
                error_log("Utilisateur non trouvé avec l'ID : $id");
            }
            return false;

        } catch (PDOException $e) {
            error_log("Erreur PDO : " . $e->getMessage());
            throw $e;
        }
    }

    public function searchUsers($searchTerm)
    {
        try {
            $db = Config::getConnexion();
            $searchTerm = '%' . $searchTerm . '%';
            
            $sql = "SELECT * FROM users WHERE 
                    classe LIKE :searchTerm OR 
                    nom LIKE :searchTerm OR 
                    prenom LIKE :searchTerm OR 
                    email LIKE :searchTerm";
                    
            $query = $db->prepare($sql);
            $query->bindParam(':searchTerm', $searchTerm);
            $query->execute();
            
            return $query->fetchAll();
            
        } catch (Exception $e) {
            error_log("Erreur de recherche : " . $e->getMessage());
            return [];
        }
    }

    public function listOffreWithPagination($currentPage, $itemsPerPage, $userType)
    {
        $offset = ($currentPage - 1) * $itemsPerPage;
        $sql = "SELECT * FROM users WHERE type LIKE :userType ORDER BY nom ASC LIMIT :offset, :itemsPerPage";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':userType', '%' . $userType . '%', PDO::PARAM_STR);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function searchUsersWithPagination($searchTerm, $page = 1, $itemsPerPage = 6, $type = '')
    {
        try {
            $db = Config::getConnexion();
            $searchTerm = '%' . $searchTerm . '%';
            $offset = ($page - 1) * $itemsPerPage;

            // Base query with optional type filtering
            $sql = "SELECT * FROM users WHERE 
                    (classe LIKE :searchTerm OR 
                     nom LIKE :searchTerm OR 
                     prenom LIKE :searchTerm OR 
                     email LIKE :searchTerm)";
                     
            if (!empty($type)) {
                $sql .= " AND type = :type";
            }

            $sql .= " LIMIT :limit OFFSET :offset";

            $query = $db->prepare($sql);
            $query->bindParam(':searchTerm', $searchTerm);
            if (!empty($type)) {
                $query->bindValue(':type', $type);
            }
            $query->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->execute();

            return $query->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getTotalUsers($type = '')
    {
        $db = Config::getConnexion();
        $sql = "SELECT COUNT(*) as total FROM users";
        
        if (!empty($type)) {
            $sql .= " WHERE type = :type";
        }
        
        try {
            $query = $db->prepare($sql);
            if (!empty($type)) {
                $query->bindValue(':type', $type);
            }
            $query->execute();
            return $query->fetch()['total'];
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function getTotalSearchResults($searchTerm)
    {
        try {
            $db = Config::getConnexion();
            $searchTerm = '%' . $searchTerm . '%';
            
            $sql = "SELECT COUNT(*) as total FROM users WHERE 
                    classe LIKE :searchTerm OR 
                    nom LIKE :searchTerm OR 
                    prenom LIKE :searchTerm OR 
                    email LIKE :searchTerm";
                    
            $query = $db->prepare($sql);
            $query->bindParam(':searchTerm', $searchTerm);
            $query->execute();
            
            $result = $query->fetch();
            return (int)$result['total'];
        } catch (Exception $e) {
            return 0;
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
            return $query->fetch(); // Retourne l'utilisateur ou false si non trouvé
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
            return $query->fetch(); // Retourne l'utilisateur si le code est valide, sinon false
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification du code de réinitialisation : " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($userId, $newPassword)
    {
        $db = Config::getConnexion();
        $sql = "UPDATE users SET mdp = :mdp, reset_code = NULL WHERE id = :id"; // Réinitialiser le code après mise à jour
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
