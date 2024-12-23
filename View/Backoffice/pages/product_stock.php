<?php
session_start();
require_once('../../FrontOffice/session_check.php');
verifierSession();

// Débogage des variables de session
error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérification de l'ID
if (!isset($_SESSION['id']) || $_SESSION['type']=='user') {
    // Si l'ID n'est pas dans la session, redirigeons vers la page de connexion
    header("Location: ../../FrontOffice/logout.php");
    exit();
}
header('Content-Type: application/json');

// Database connection settings
$host = "localhost";
$dbname = "emprunt"; // Replace with your database name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// Fetch product stock data
try {
    $sql = "SELECT Nom_Produit, Qte FROM produit";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $product_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($product_stock === false) {
        throw new PDOException("Failed to fetch product stock.");
    }
} catch (PDOException $e) {
    die(json_encode(["error" => "Error fetching product stock: " . $e->getMessage()]));
}

// Prepare response
$response = $product_stock;

echo json_encode($response);
?>
