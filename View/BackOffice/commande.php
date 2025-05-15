<?php
class Commande {
    private ?int $Id_commande;
    private ?string $Adresse_client;
    private ?int $Tel_client;
    private ?string $Nom_client;
    private ?string $Prenom_client;
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Create a new order
    public function createCommande(string $Adresse_client, int $Tel_client, string $Nom_client, string $Prenom_client): bool {
        $sql = "INSERT INTO commande (Adresse_client, Tel_client, Nom_client, Prenom_client) VALUES (:Adresse_client, :Tel_client, :Nom_client, :Prenom_client)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':Adresse_client' => $Adresse_client,
            ':Tel_client' => $Tel_client,
            ':Nom_client' => $Nom_client,
            ':Prenom_client' => $Prenom_client
        ]);
    }

    // Read an order by ID
    public function getCommandeById(int $Id_commande): ?array {
        $sql = "SELECT * FROM commande WHERE Id_commande = :Id_commande";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':Id_commande' => $Id_commande]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Update an existing order
    public function updateCommande(int $Id_commande, string $Adresse_client, int $Tel_client, string $Nom_client, string $Prenom_client): bool {
        $sql = "UPDATE commande SET Adresse_client = :Adresse_client, Tel_client = :Tel_client, Nom_client = :Nom_client, Prenom_client = :Prenom_client WHERE Id_commande = :Id_commande";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':Id_commande' => $Id_commande,
            ':Adresse_client' => $Adresse_client,
            ':Tel_client' => $Tel_client,
            ':Nom_client' => $Nom_client,
            ':Prenom_client' => $Prenom_client
        ]);
    }

    // Delete an order
    public function deleteCommande(int $Id_commande): bool {
        $sql = "DELETE FROM commande WHERE Id_commande = :Id_commande";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':Id_commande' => $Id_commande]);
    }

    // Get all orders
    public function getAllCommandes(): array {
        $sql = "SELECT * FROM commande";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
