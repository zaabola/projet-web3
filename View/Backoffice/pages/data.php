<?php
header('Content-Type: application/json');

// Database connection settings
$host = "localhost";
$dbname = "empreinte1"; // Replace with your database name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// Fetch total number of commands
try {
    $sql_commands = "SELECT COUNT(Id_commande) AS command_count FROM commande";
    $stmt_commands = $pdo->prepare($sql_commands);
    $stmt_commands->execute();
    $command_data = $stmt_commands->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(["error" => "Error fetching command data: " . $e->getMessage()]));
}

// Fetch total number of reclamations
try {
    $sql_reclamations = "SELECT COUNT(Id_reclamation) AS reclamation_count FROM reclamation";
    $stmt_reclamations = $pdo->prepare($sql_reclamations);
    $stmt_reclamations->execute();
    $reclamation_data = $stmt_reclamations->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(["error" => "Error fetching reclamation data: " . $e->getMessage()]));
}

// Prepare response
$response = [
    "command_count" => $command_data['command_count'],
    "reclamation_count" => $reclamation_data['reclamation_count']
];

echo json_encode($response);
?>