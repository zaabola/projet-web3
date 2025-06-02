<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "emprunt";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Fetch total cost data
try {
  $stmt = $pdo->query("SELECT total_cost FROM panier_items");
  $totalCosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Send JSON response
  header('Content-Type: application/json');
  echo json_encode($totalCosts);
} catch (Exception $e) {
  echo "<p style='color: red;'>Error fetching total costs: " . $e->getMessage() . "</p>";
}
