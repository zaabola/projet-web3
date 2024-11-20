<?php
require_once("C:/xampp/htdocs/reservation/config.php");
include 'C:/xampp/htdocs/reservation/Model/res.php';

$host = "localhost";
$dbname = "emprunt";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-reservation'])){
    $nom = htmlspecialchars($_POST['last-name'] ?? '');
    $prenom = htmlspecialchars($_POST['first-name'] ?? '');
    $mail = htmlspecialchars($_POST['mail'] ?? '');
    $tel = htmlspecialchars($_POST['tel'] ?? '');
    $destination = htmlspecialchars($_POST['destination'] ?? '');
    $commentaire = htmlspecialchars($_POST['commentaire'] ?? '');
    try {
        $sql = "INSERT INTO reservation (nom, prenom, mail, tel, destination, commentaire, date) 
                VALUES (:nom, :prenom, :mail, :tel, :destination, :commentaire, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $nom,
            ':prenom' => $prenom,
            ':mail' => $mail,
            ':tel' => $tel,
            ':destination' => $destination
            ':commentaire' => $commentaire,
        ]);
        $success = "reservation ajoutée avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>