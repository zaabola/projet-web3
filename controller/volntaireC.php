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

    public function rate($rate, $id_u, $id_v) 
    {
        // SQL query to insert a rating into the 'rating' table
        $sql = "INSERT INTO `rating`(`id_v`, `id_u`, `rate`) VALUES (:id_v, :id_u, :rate) ON DUPLICATE KEY UPDATE `rate` = VALUES(`rate`);";
        
        // Get the database connection
        $db = config::getConnexion();
        
        try {
            // Prepare the query
            $query = $db->prepare($sql);
            
            // Execute the query with the provided parameters
            $query->execute([
                'id_v' => $id_v,
                'id_u' => $id_u,
                'rate' => $rate
            ]);
            
            // Redirect after successful insertion
            header('Location: index.php');
            exit();  // It's important to call exit() after header() to stop further script execution
        } catch (Exception $e) {
            // Handle errors and display the exception message
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    
    // Read all Portfolio records
    public function israte($idu,$idv)
    {
        $sql = "SELECT * FROM `rating` WHERE `id_u` =" . $idu . " AND `id_v` =" . $idv;
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            $f = $list->fetch();
            return $f; // Return all portfolio entries
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Read all Portfolio records
    public function note($idv)
    {
        $sql = "SELECT 
                    IFNULL(FORMAT(SUM(`rate`) / NULLIF(COUNT(*), 0), 1), '0') AS `note`, 
                    COUNT(*) AS `lignes` 
                FROM 
                    `rating` 
                WHERE 
                    `id_v` = " . $idv;
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            $f = $list->fetch();
            return $f; // Return all portfolio entries
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
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

    public function search($d)
    {
        $sql = "SELECT v.*, 
                    CASE 
                        WHEN p.id_portfolio IS NOT NULL THEN 1 
                        ELSE 0 
                    END AS has_portfolio
                FROM volontaire v
                LEFT JOIN portfolio p ON v.id = p.id_volontaire
                WHERE v.nom LIKE :search 
                OR v.prenom LIKE :search 
                OR v.numero LIKE :search";
                
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'search' => "%$d%" // Bind the search term with wildcards
            ]);
            return $query->fetchAll(); // Fetch all matching results
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
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