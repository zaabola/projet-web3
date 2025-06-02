<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";


try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Erreur de connexion : ' . $e->getMessage();
  exit;
}

// Récupérer les articles du thème spécifié
$theme_id = isset($_GET['theme_id']) ? $_GET['theme_id'] : null;

if ($theme_id) {
  $query = "SELECT a.*, t.titre AS theme_titre 
              FROM articles a 
              LEFT JOIN theme t ON a.id = t.id 
              WHERE a.id = :theme_id";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
  $stmt->execute();
  $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $articles = [];
}

echo json_encode($articles);
