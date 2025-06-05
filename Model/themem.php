<?php

class theme
{
  private ?int $id;
  private ?string $titre;
  private ?string $description;
  private ?string $image;

  public function __construct(
    ?int $id = null,
    ?string $titre = null,
    ?string $description = null,
    ?string $image = null
  ) {
    $this->id = $id;
    $this->titre = $titre;
    $this->description = $description;
    $this->image = $image;
  }

  /*  public function __construct(
    ?int $id = null,
    ?string $titre,
    ?string $description,
    ?string $image
  ) {
    $this->id = $id;
    $this->titre = $titre;
    $this->description = $description;
    $this->image = $image;
}*/

  // Getters and Setters

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function gettitre(): ?string
  {
    return $this->titre;
  }

  public function settitre(?string $titre): void
  {
    $this->titre = $titre;
  }

  public function getdescription(): ?string
  {
    return $this->description;
  }

  public function setdescription(?string $description): void
  {
    $this->description = $description;
  }
  public function getimage(): ?string
  {
    return $this->image;
  }

  public function setimage(?string $image): void
  {
    $this->image = $image;
  }
}
