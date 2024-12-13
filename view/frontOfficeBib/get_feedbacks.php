<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=Emprunt", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $article_id = isset($_GET['article_id']) ? (int)$_GET['article_id'] : 0;
    
    $query = "SELECT commentaire FROM feed_back WHERE Id_article = :article_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($feedbacks);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données']);
}
?> 