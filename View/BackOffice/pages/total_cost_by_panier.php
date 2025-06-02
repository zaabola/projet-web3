<?php
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

// Fetch total costs grouped by id_panier
try {
  $sql = "SELECT id_panier, SUM(total_cost) AS total_cost_sum FROM panier_items GROUP BY id_panier";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $total_cost_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die(json_encode(["error" => "Error fetching total cost data: " . $e->getMessage()]));
}

// Prepare response
$response = $total_cost_data;

echo json_encode($response);
