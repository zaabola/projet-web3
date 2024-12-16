<?php
class FeedBack {
    private $Id_feedback;
    private $Id_article;
    private $commentaire;
    private $date_creation;

    // Constructeur
    public function __construct($Id_article = null, $commentaire = null) {
        $this->Id_article = $Id_article;
        $this->commentaire = $commentaire;
        $this->date_creation = date('Y-m-d H:i:s');
    }

    // Getters
    public function getIdFeedback() {
        return $this->Id_feedback;
    }

    public function getIdArticle() {
        return $this->Id_article;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    // Setters
    public function setIdFeedback($Id_feedback) {
        $this->Id_feedback = $Id_feedback;
    }

    public function setIdArticle($Id_article) {
        $this->Id_article = $Id_article;
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    public function setDateCreation($date_creation) {
        $this->date_creation = $date_creation;
    }
}
?>
