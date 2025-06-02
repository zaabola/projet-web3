<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id']) || $_SESSION['type'] == 'user') {
  // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
  header("Location: ../../FrontOffice/logout.php");
  exit();
}
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

// Récupération de l'ID de l'article à supprimer
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID de l'article invalide ou non spécifié.");
}
$article_id = (int)$_GET['id'];

// Récupération de l'ID du thème pour la redirection après la suppression
if (!isset($_GET['theme_id']) || !is_numeric($_GET['theme_id'])) {
  die("ID du thème invalide ou non spécifié.");
}
$theme_id = (int)$_GET['theme_id'];

// Suppression de l'article
$deleteQuery = "DELETE FROM articles WHERE Id_article = :article_id";
$stmt = $pdo->prepare($deleteQuery);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();

// Redirection vers la page des articles du thème
header("Location: artc.php?id=$theme_id");
exit;
