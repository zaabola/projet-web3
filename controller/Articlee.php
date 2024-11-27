<?php
include_once(__DIR__ . '/../config.php'); // Including the database connection
include_once(__DIR__ . '/../model/article.php'); // Including the Theme model
include_once(__DIR__ . '/../model/themem.php'); // Including the Theme model

class ArticlesController {
    private $db;

    /*public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }*/

    // Liste des Articless
    public function listArticless() {
        $query = "SELECT a.*, t.titre AS theme_titre 
                  FROM Articless a 
                  LEFT JOIN themes t ON a.id = t.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un Articles
    public function addArticles($Articles) {
        $query = "INSERT INTO Articless (Titre_Articles, Description_Articles, image_Articles, id)
                  VALUES (:titre, :description, :image, :id)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':titre', $Articles->getTitreArticles());
        $stmt->bindValue(':description', $Articles->getDescriptionArticles());
        $stmt->bindValue(':image', $Articles->getImageArticles());
        $stmt->bindValue(':id', $Articles->getIdTheme());

        return $stmt->execute();
    }

    // Modifier un Articles
    public function updateArticles($Articles) {
        $query = "UPDATE Articless 
                  SET Titre_Articles = :titre, Description_Articles = :description, 
                      image_Articles = :image, id = :id 
                  WHERE Id_Articles = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id', $Articles->getIdArticles());
        $stmt->bindValue(':titre', $Articles->getTitreArticles());
        $stmt->bindValue(':description', $Articles->getDescriptionArticles());
        $stmt->bindValue(':image', $Articles->getImageArticles());
        $stmt->bindValue(':id', $Articles->getIdTheme());

        return $stmt->execute();
    }

    // Supprimer un Articles
    public function deleteArticles($Id_Articles) {
        $query = "DELETE FROM Articless WHERE Id_Articles = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $Id_Articles);
        return $stmt->execute();
    }

    // Obtenir un Articles par ID
    public function getArticlesById($Id_Articles) {
        $query = "SELECT * FROM Articless WHERE Id_Articles = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $Id_Articles);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getArticlessByTheme($id)
{
    $sql = "SELECT * FROM Articles WHERE id = :id";
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    try {
        $query->execute();
        return $query->fetchAll();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}

}
?>
