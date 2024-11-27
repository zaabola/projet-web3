<?php
require_once 'C:/xampp/htdocs/reservation/config.php';
require_once 'C:/xampp/htdocs/reservation/Model/res.php';

class GestionBus {
    private $pdo;

    public function __construct() {
        $this->pdo = Config::getConnexion();
    }

    // CREATE: Add a new bus
    public function addBus($matricule, $nomChauffeur, $depart, $nbrPlace, $destination) {
        try {
            $sql = "INSERT INTO bus (matricule, nom_chauffeur, depart, nbr_place, destination)
                    VALUES (:matricule, :nomChauffeur, :depart, :nbrPlace, :destination)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':matricule' => $matricule,
                ':nomChauffeur' => $nomChauffeur,
                ':depart' => $depart,
                ':nbrPlace' => $nbrPlace,
                ':destination' => $destination
            ]);
            echo "Bus added successfully!";
        } catch (Exception $e) {
            echo "Error adding bus: " . $e->getMessage();
        }
    }

    // READ: Get all buses
    public function getAllBuses() {
        try {
            $sql = "SELECT * FROM bus";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching buses: " . $e->getMessage();
        }
    }

    // READ: Get a single bus by matricule
    public function getBusByMatricule($matricule) {
        try {
            $sql = "SELECT * FROM bus WHERE matricule = :matricule";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':matricule' => $matricule]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching bus: " . $e->getMessage();
        }
    }

    // UPDATE: Update bus details
    public function updateBus($matricule, $nomChauffeur, $depart, $nbrPlace, $destination) {
        try {
            $sql = "UPDATE bus
                    SET nom_chauffeur = :nomChauffeur,
                        depart = :depart,
                        nbr_place = :nbrPlace,
                        destination = :destination
                    WHERE matricule = :matricule";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':matricule' => $matricule,
                ':nomChauffeur' => $nomChauffeur,
                ':depart' => $depart,
                ':nbrPlace' => $nbrPlace,
                ':destination' => $destination
            ]);
            echo "Bus updated successfully!";
        } catch (Exception $e) {
            echo "Error updating bus: " . $e->getMessage();
        }
    }

    // DELETE: Delete a bus by matricule
    public function deleteBus($matricule) {
        try {
            $sql = "DELETE FROM bus WHERE matricule = :matricule";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':matricule' => $matricule]);
            echo "Bus deleted successfully!";
        } catch (Exception $e) {
            echo "Error deleting bus: " . $e->getMessage();
        }
    }
}
?>
