<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/res.php');

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

    public function createReservation(reservation $reservation)
    {
        try {
            // Step 1: Check if there are any buses available for the given destination
            $sql = "SELECT matricule, nbr_place FROM bus WHERE destination = :destination";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':destination' => $reservation->getDestination()]);
            $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Step 2: If no bus is available, throw an error
            if (!$buses) {
                throw new Exception("Excursion pas disponible.");
            }

            // Step 3: Try to find a bus with available seats
            $availableBus = null;
            foreach ($buses as $bus) {
                // Count how many reservations are already made for this bus
                $sql = "SELECT COUNT(r.id_reservation) AS reservations_count 
                        FROM reservation r
                        WHERE r.matricule = :matricule";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':matricule' => $bus['matricule']]);
                $reservationCount = $stmt->fetch(PDO::FETCH_ASSOC)['reservations_count'];

                // Check if there are available seats on this bus
                if ($bus['nbr_place'] > $reservationCount) {
                    $availableBus = $bus; // Bus found with available seats
                    break;
                }
            }

            // Step 4: If no available bus is found, throw an error
            if (!$availableBus) {
                throw new Exception("Pas de place disponible.");
            }

            // Step 5: Assign the bus matricule to the reservation
            $matricule = $availableBus['matricule'];
            $reservation->setMatricule($matricule);

            // Step 6: Insert the reservation into the database
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
            throw $e; // Custom exception for unavailable excursion or no available seats
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

    public function updateReservation(reservation $reservation)
    {
        try {
            // Fetch all buses for the updated destination
            $sql = "SELECT matricule, nbr_place FROM bus WHERE destination = :destination";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':destination' => $reservation->getDestination()]);
            $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Step 1: Check if no buses are available for the updated destination
            if (!$buses) {
                throw new Exception("Aucun bus disponible pour cette destination.");
            }

            // Step 2: Try to find a bus with available seats for the updated destination
            $availableBus = null;
            foreach ($buses as $bus) {
                // Count how many reservations are already made for this bus
                $sql = "SELECT COUNT(r.id_reservation) AS reservations_count 
                        FROM reservation r
                        WHERE r.matricule = :matricule";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':matricule' => $bus['matricule']]);
                $reservationCount = $stmt->fetch(PDO::FETCH_ASSOC)['reservations_count'];

                // Check if there are available seats on this bus
                if ($bus['nbr_place'] > $reservationCount) {
                    $availableBus = $bus; // Bus found with available seats
                    break;
                }
            }

            // Step 3: If no available bus is found, throw an error
            if (!$availableBus) {
                throw new Exception("Pas de place disponible sur un bus pour cette destination.");
            }

            // Step 4: Assign the bus matricule to the reservation
            $matricule = $availableBus['matricule'];
            $reservation->setMatricule($matricule);

            // Step 5: Update the reservation in the database
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

            return true; // Success
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        } catch (Exception $e) {
            throw $e; // Custom exception for unavailable excursion or no available seats
        }
    }


}
?>
