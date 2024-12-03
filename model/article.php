<?php

include_once 'themem.php'; // Inclusion du modèle Theme

class Article {
    private ?int $Id_article; // Clé primaire auto-incrémentée
    private ?string $Titre_article; // Titre de l'article
    private ?string $Description_article; // Description de l'article
    private ?string $image_article; // Image associée à l'article
    private ?string $bibliographie; // Bibliographie de l'article
    private ?string $date_crt; // Date de création
    private ?string $date_maj; // Date de mise à jour
    private ?int $archivage; // Archivage : 0 = actif, 1 = archivé
    private ?int $id; // Clé étrangère pour la table `theme`
    private ?Theme $theme; // Objet Theme associé

    // Constructor
    public function __construct(
        ?int $Id_article = null,
        ?string $Titre_article = null,
        ?string $Description_article = null,
        ?string $image_article = null,
        ?string $bibliographie = null,
        ?string $date_crt = null,
        ?string $date_maj = null,
        ?int $archivage = 0,
        ?int $id = null,
        ?Theme $theme = null // Injecter l'objet Theme
    ) {
        $this->Id_article = $Id_article;
        $this->Titre_article = $Titre_article;
        $this->Description_article = $Description_article;
        $this->image_article = $image_article;
        $this->bibliographie = $bibliographie;
        $this->date_crt = $date_crt;
        $this->date_maj = $date_maj;
        $this->archivage = $archivage;
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

    public function getBibliographie(): ?string {
        return $this->bibliographie;
    }

    public function setBibliographie(?string $bibliographie): void {
        $this->bibliographie = $bibliographie;
    }

    public function getDateCrt(): ?string {
        return $this->date_crt;
    }

    public function setDateCrt(?string $date_crt): void {
        $this->date_crt = $date_crt;
    }

    public function getDateMaj(): ?string {
        return $this->date_maj;
    }

    public function setDateMaj(?string $date_maj): void {
        $this->date_maj = $date_maj;
    }

    public function getArchivage(): ?int {
        return $this->archivage;
    }

    public function setArchivage(?int $archivage): void {
        $this->archivage = $archivage;
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
