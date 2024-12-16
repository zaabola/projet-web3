<?php

include_once __DIR__ . '/../model/feed_back.php'; // Inclure le modèle Feedback
include_once(__DIR__ . '/../config.php'); // Inclusion de la connexion à la base de données
include_once(__DIR__ . '/../model/article.php'); // Inclusion du modèle Article

class FeedbackController {
    private $db; // Instance de la base de données

    public function __construct() {
        $this->db = Database::getInstance()->getConnection(); // Obtenir la connexion à la base
    }

    /**
     * Ajouter un nouveau feedback.
     */
    public function addFeedback(Feedback $feedback): bool {
        $query = "INSERT INTO feed_back (id_article, commentaire) VALUES (:id_article, :commentaire)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id_article' => $feedback->getIdArticle(),
            ':commentaire' => $feedback->getCommentaire()
        ]);
    }

    /**
     * Récupérer tous les feedbacks.
     */
    public function getAllFeedbacks(): array {
        $query = "SELECT * FROM feed_back";
        $stmt = $this->db->query($query);

        $feedbacks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feedbacks[] = new Feedback(
                $row['id_feed_back'],
                $row['id_article'],
                $row['commentaire']
            );
        }

        return $feedbacks;
    }

    /**
     * Récupérer les feedbacks associés à un article.
     */
    public function getFeedbacksByArticle(int $id_article): array {
        $query = "SELECT * FROM feed_back WHERE id_article = :id_article";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_article' => $id_article]);

        $feedbacks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feedbacks[] = new Feedback(
                $row['id_feed_back'],
                $row['id_article'],
                $row['commentaire']
            );
        }

        return $feedbacks;
    }

    /**
     * Mettre à jour un feedback existant.
     */
    public function updateFeedback(Feedback $feedback): bool {
        $query = "UPDATE feed_back SET commentaire = :commentaire WHERE id_feed_back = :id_feed_back";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':commentaire' => $feedback->getCommentaire(),
            ':id_feed_back' => $feedback->getIdFeedBack()
        ]);
    }

    /**
     * Supprimer un feedback par son ID.
     */
    public function deleteFeedback(int $id_feed_back): bool {
        $query = "DELETE FROM feed_back WHERE id_feed_back = :id_feed_back";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([':id_feed_back' => $id_feed_back]);
    }
}

?>
