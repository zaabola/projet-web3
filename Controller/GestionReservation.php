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
            $sql = "INSERT INTO reservation (nom, prenom, mail, tel, destination, commentaire, date) 
                    VALUES (:nom, :prenom, :mail, :tel, :destination, :commentaire, :date)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $reservation->getNom(),
                ':prenom' => $reservation->getPrenom(),
                ':mail' => $reservation->getMail(),
                ':tel' => $reservation->getTel(),
                ':destination' => $reservation->getDestination(),
                ':commentaire' => $reservation->getCommentaire(),
                ':date' => $reservation->getDate()->format('Y-m-d H:i:s'),
            ]);

            return true; // Return true if the operation was successful
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la réservation : " . $e->getMessage());
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
            $sql = "UPDATE reservation SET 
                        nom = :nom, 
                        prenom = :prenom, 
                        mail = :mail, 
                        tel = :tel, 
                        destination = :destination, 
                        commentaire = :commentaire, 
                        date = :date 
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
                ':id_reservation' => $reservation->getId(),
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }
}
?>
