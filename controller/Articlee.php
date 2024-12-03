<?php
include_once(__DIR__ . '/../config.php'); // Inclusion de la connexion à la base de données
include_once(__DIR__ . '/../model/article.php'); // Inclusion du modèle Article
include_once(__DIR__ . '/../model/themem.php'); // Inclusion du modèle Theme

class ArticlesController {
    private $db;

    /*public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }*/

    // Liste des Articles
    public function listArticless() {
        $query = "SELECT a.*, t.titre AS theme_titre 
                  FROM articless a 
                  LEFT JOIN themes t ON a.id = t.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un Article
    public function addArticles($Articles) {
        $query = "INSERT INTO Articles (Titre_Articles, Description_Articles, image_Articles, bibliographie, date_crt, archivage, id)
                  VALUES (:titre, :description, :image, :bibliographie, NOW(), :archivage, :id)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':titre', $Articles->getTitreArticles());
        $stmt->bindValue(':description', $Articles->getDescriptionArticles());
        $stmt->bindValue(':image', $Articles->getImageArticles());
        $stmt->bindValue(':bibliographie', $Articles->getBibliographie());
        $stmt->bindValue(':archivage', $Articles->getArchivage(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $Articles->getIdTheme());

        return $stmt->execute();
    }

    // Modifier un Article
    public function updateArticles($Articles) {
        $query = "UPDATE Articless 
                  SET Titre_Articles = :titre, Description_Articles = :description, 
                      image_Articles = :image, bibliographie = :bibliographie, 
                      date_maj = NOW(), archivage = :archivage, 
                      id = :id 
                  WHERE Id_article = :article_id";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':article_id', $Articles->getIdArticles());
        $stmt->bindValue(':titre', $Articles->getTitreArticles());
        $stmt->bindValue(':description', $Articles->getDescriptionArticle());
        $stmt->bindValue(':image', $Articles->getImageArticles());
        $stmt->bindValue(':bibliographie', $Articles->getBibliographie());
        $stmt->bindValue(':archivage', $Articles->getArchivage(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $Articles->getIdTheme());

        return $stmt->execute();
    }

    // Supprimer un Article
    public function deleteArticles($Id_Articles) {
        $query = "DELETE FROM Articless WHERE Id_Articles = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $Id_Articles);
        return $stmt->execute();
    }

    // Obtenir un Article par ID
    public function getArticlesById($Id_Articles) {
        $query = "SELECT a.*, t.titre AS theme_titre 
                  FROM Articless a 
                  LEFT JOIN themes t ON a.id = t.id 
                  WHERE a.Id_article = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $Id_Articles);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtenir des Articles par thème
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
