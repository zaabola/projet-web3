<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Complaint</span>
          </a>
        </li>
    </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
    </div>
  </aside>
</body>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
<?php
// Error reporting to help with debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$dbname = "empreinte1"; // Replace with your database name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission for Create, Update, Delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create operation
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $id_commande = $_POST['Id_commande'];
        $commentaire = $_POST['Commentaire'];
        $nom = $_POST['Nom'];
        $prenom = $_POST['Prenom'];
        $email = $_POST['Email'];
        $tel = $_POST['Tel'];

        $sql = "INSERT INTO reclamation (Id_commande, Commentaire, Nom, Prenom, Email, Tel) 
                VALUES (:id_commande, :commentaire, :nom, :prenom, :email, :tel)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(compact('id_commande', 'commentaire', 'nom', 'prenom', 'email', 'tel'))) {
            $message = "Reclamation created successfully.";
        } else {
            $message = "Failed to create reclamation.";
        }
    }

    // Update operation
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id_reclamation = $_POST['Id_reclamation'];
        $id_commande = $_POST['Id_commande'];
        $commentaire = $_POST['Commentaire'];
        $nom = $_POST['Nom'];
        $prenom = $_POST['Prenom'];
        $email = $_POST['Email'];
        $tel = $_POST['Tel'];

        $sql = "UPDATE reclamation SET Id_commande = :id_commande, Commentaire = :commentaire, Nom = :nom, 
                Prenom = :prenom, Email = :email, Tel = :tel WHERE Id_reclamation = :id_reclamation";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(compact('id_reclamation', 'id_commande', 'commentaire', 'nom', 'prenom', 'email', 'tel'))) {
            $message = "Reclamation updated successfully.";
        } else {
            $message = "Failed to update reclamation.";
        }
    }

    // Delete operation
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id_reclamation = $_POST['Id_reclamation'];

        $sql = "DELETE FROM reclamation WHERE Id_reclamation = :id_reclamation";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['id_reclamation' => $id_reclamation])) {
            $message = "Reclamation deleted successfully.";
        } else {
            $message = "Failed to delete reclamation.";
        }
    }
}

// Fetch all reclamations to display
$sql = "SELECT * FROM reclamation";
$stmt = $pdo->query($sql);
$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reclamation CRUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input, form button {
            margin: 5px 0;
            padding: 8px;
            width: 100%;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #ordersList div {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Reclamation CRUD</h1>
    
    <!-- Message output -->
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Form to create a new reclamation -->
    <form method="POST">
        <h2>Create a New Reclamation</h2>
        <input type="number" name="Id_commande" placeholder="Commande ID" required>
        <input type="text" name="Commentaire" placeholder="Commentaire" required>
        <input type="text" name="Nom" placeholder="Nom" required>
        <input type="text" name="Prenom" placeholder="Prenom" required>
        <input type="email" name="Email" placeholder="Email" required>
        <input type="text" name="Tel" placeholder="Tel" required>
        <input type="hidden" name="action" value="create">
        <button type="submit" class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Create Reclamation</button>
    </form>

    <!-- Form to update an existing reclamation -->
    <form method="POST">
        <h2>Update a Reclamation</h2>
        <input type="number" name="Id_reclamation" placeholder="Reclamation ID" required>
        <input type="number" name="Id_commande" placeholder="Commande ID" required>
        <input type="text" name="Commentaire" placeholder="Commentaire" required>
        <input type="text" name="Nom" placeholder="Nom" required>
        <input type="text" name="Prenom" placeholder="Prenom" required>
        <input type="email" name="Email" placeholder="Email" required>
        <input type="text" name="Tel" placeholder="Tel" required>
        <input type="hidden" name="action" value="update">
        <button type="submit"  class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Update Reclamation</button>
    </form>

    <!-- Form to delete a reclamation -->
    <form method="POST">
        <h2>Delete a Reclamation</h2>
        <input type="number" name="Id_reclamation" placeholder="Reclamation ID" required>
        <input type="hidden" name="action" value="delete">
        <button type="submit "  class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white">Delete Reclamation</button>
    </form>

    <!-- Display all reclamations -->
    <h2>All Reclamations</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Commande ID</th>
            <th>Commentaire</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Email</th>
            <th>Tel</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reclamations as $reclamation): ?>
            <tr>
                <td><?php echo $reclamation['Id_reclamation']; ?></td>
                <td><?php echo $reclamation['Id_commande']; ?></td>
                <td><?php echo $reclamation['Commentaire']; ?></td>
                <td><?php echo $reclamation['Nom']; ?></td>
                <td><?php echo $reclamation['Prenom']; ?></td>
                <td><?php echo $reclamation['Email']; ?></td>
                <td><?php echo $reclamation['Tel']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <script>
                async function fetchReclamations() {
            try {
                const response = await fetch('reclamation.php');
                const data = await response.json();
                
                if (data.length === 0) {
                    document.getElementById('reclamationsList').innerHTML = 'No reclamations found.';
                } else {
                    let tableHTML = '<table>';
                    tableHTML += `<tr>
                                    <th>ID</th>
                                    <th>Commande ID</th>
                                    <th>Commentaire</th>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Email</th>
                                    <th>Tel</th>
                                  </tr>`;
                    data.forEach(row => {
                        tableHTML += `<tr>
                                        <td>${row.Id_reclamation}</td>
                                        <td>${row.Id_commande}</td>
                                        <td>${row.Commentaire}</td>
                                        <td>${row.Nom}</td>
                                        <td>${row.Prenom}</td>
                                        <td>${row.Email}</td>
                                        <td>${row.Tel}</td>
                                      </tr>`;
                    });
                    tableHTML += '</table>';
                    document.getElementById('reclamationsList').innerHTML = tableHTML;
                }
            } catch (error) {
                console.error('Error fetching reclamations:', error);
                document.getElementById('reclamationsList').innerHTML = 'Error loading reclamations.';
            }
        }
    </script>

</body>
</html>
