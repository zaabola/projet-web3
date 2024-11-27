<?php

include_once 'themem.php'; // Inclusion du modèle Theme

class Article {
    private ?int $Id_article; // Clé primaire auto-incrémentée
    private ?string $Titre_article; // Titre de l'article
    private ?string $Description_article; // Description de l'article
    private ?string $image_article; // Image associée à l'article
    private ?int $id; // Clé étrangère pour la table `theme`
    private ?Theme $theme; // Objet Theme associé

    // Constructor
    public function __construct(
        ?int $Id_article = null,
        ?string $Titre_article = null,
        ?string $Description_article = null,
        ?string $image_article = null,
        ?int $id = null,
        ?Theme $theme = null // Injecter l'objet Theme
    ) {
        $this->Id_article = $Id_article;
        $this->Titre_article = $Titre_article;
        $this->Description_article = $Description_article;
        $this->image_article = $image_article;
        $this->id = $id;
        $this->theme = $theme;
    }

    // Getters et Setters

    public function getIdArticle(): ?int {
        return $this->Id_article;
    }

    public function setIdArticle(?int $Id_article): void {
        $this->Id_article = $Id_article;
    }

    public function getTitreArticle(): ?string {
        return $this->Titre_article;
    }

    public function setTitreArticle(?string $Titre_article): void {
        $this->Titre_article = $Titre_article;
    }

    public function getDescriptionArticle(): ?string {
        return $this->Description_article;
    }

    public function setDescriptionArticle(?string $Description_article): void {
        $this->Description_article = $Description_article;
    }

    public function getImageArticle(): ?string {
        return $this->image_article;
    }

    public function setImageArticle(?string $image_article): void {
        $this->image_article = $image_article;
    }

    public function getIdTheme(): ?int {
        return $this->id;
    }

    public function setIdTheme(?int $id): void {
        $this->id = $id;
    }

    public function getTheme(): ?Theme {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): void {
        $this->theme = $theme;
    }
}

?>
