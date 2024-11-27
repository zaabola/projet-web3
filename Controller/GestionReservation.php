<?php
require_once 'C:/xampp/htdocs/reservation/config.php';
require_once 'C:/xampp/htdocs/reservation/Model/res.php';

class GestionReservation
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Config::getConnexion();
    }

    public function getAllReservations()
    {
        try {
            $sql = "SELECT * FROM reservation ORDER BY date DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des réservations : " . $e->getMessage());
        }
    }

    public function createReservation(reservevation $reservation)
    {
        try {
            // Step 1: Check if a bus is available for the given destination
            $sql = "SELECT matricule FROM bus WHERE destination = :destination LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':destination' => $reservation->getDestination()]);
            $bus = $stmt->fetch(PDO::FETCH_ASSOC);

            // Step 2: If no bus is available, throw an error
            if (!$bus) {
                throw new Exception("Excursion pas disponible");
            }

            // Step 3: Assign the bus matricule to the reservation
            $matricule = $bus['matricule'];
            $reservation->setMatricule($matricule);

            // Step 4: Insert the reservation into the database
            $sql = "INSERT INTO reservation (nom, prenom, mail, tel, destination, commentaire, date, matricule) 
                    VALUES (:nom, :prenom, :mail, :tel, :destination, :commentaire, :date, :matricule)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $reservation->getNom(),
                ':prenom' => $reservation->getPrenom(),
                ':mail' => $reservation->getMail(),
                ':tel' => $reservation->getTel(),
                ':destination' => $reservation->getDestination(),
                ':commentaire' => $reservation->getCommentaire(),
                ':date' => $reservation->getDate()->format('Y-m-d H:i:s'),
                ':matricule' => $matricule
            ]);

            return true; // Success
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la réservation : " . $e->getMessage());
        } catch (Exception $e) {
            throw $e; // Custom exception for unavailable excursion
        }
    }


    public function deleteReservation(int $id_reservation)
    {
        try {
            $sql = "DELETE FROM reservation WHERE id_reservation = :id_reservation";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_reservation' => $id_reservation]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    public function getReservationById(int $id_reservation)
    {
        try {
            $sql = "SELECT * FROM reservation WHERE id_reservation = :id_reservation";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_reservation' => $id_reservation]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de la réservation : " . $e->getMessage());
        }
    }

    public function updateReservation(reservevation $reservation)
    {
        try {
            // Fetch the appropriate `matricule` based on the updated destination
            $sql = "SELECT matricule FROM bus WHERE destination = :destination LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':destination' => $reservation->getDestination()]);
            $bus = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bus) {
                throw new Exception("Aucun bus disponible pour cette destination.");
            }

            $matricule = $bus['matricule'];

            // Update the reservation in the database
            $sql = "UPDATE reservation SET 
                        nom = :nom, 
                        prenom = :prenom, 
                        mail = :mail, 
                        tel = :tel, 
                        destination = :destination, 
                        commentaire = :commentaire, 
                        date = :date,
                        matricule = :matricule 
                    WHERE id_reservation = :id_reservation";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $reservation->getNom(),
                ':prenom' => $reservation->getPrenom(),
                ':mail' => $reservation->getMail(),
                ':tel' => $reservation->getTel(),
                ':destination' => $reservation->getDestination(),
                ':commentaire' => $reservation->getCommentaire(),
                ':date' => $reservation->getDate()->format('Y-m-d H:i:s'),
                ':matricule' => $matricule,
                ':id_reservation' => $reservation->getId(),
            ]);

            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

}
?>
