<?php
include '../../../controller/theme.php';
$travelOfferC = new ThemeController();
$list = $travelOfferC->listtheme();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet">
  <link id="pagestyle" href="../assets/css/material-dashboard.min.css?v=3.2.0" rel="stylesheet">
  <title>Gestion Thèmes</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }
    th {
      background-color: #f8f9fa;
    }
    img {
      max-width: 100px;
      height: auto;
      border-radius: 5px;
    }
    .btn {
      margin-right: 5px;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start bg-white">
    <!-- Sidebar Content -->
    <div class="sidenav-header">
      <a class="navbar-brand" href="#">
        <img src="../assets/img/logo-ct-dark.png" alt="Logo" width="26" height="26">
        <span>Emreinte</span>
      </a>
    </div>
    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="Gestion Thèmes" href="bib.php">
          <i class="material-icons"></i>
          <span>Gestion Thèmes</span>
        </a>
      </li>
    </ul>
  </aside>

  <main class="main-content">
    <div class="container-fluid py-4">
      <a class="btn btn-outline-dark mb-4" href="ct.php">Créer un thème</a>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Liste des Thèmes</h6>
            </div>
            <div class="card-body">
              <table>
                <thead>
                  <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($list as $theme) { ?>
                    <tr>
                      <td><?php echo htmlspecialchars($theme['titre']); ?></td>
                      <td><?php echo htmlspecialchars($theme['description']); ?></td>
                      <td>
                        <img src="../../frontOfficeBib/<?php echo htmlspecialchars($theme['image']); ?>" alt="Theme Image">
                      </td>
                      <td>
                        <a href="update.php?id=<?php echo urlencode($theme['id']); ?>" class="btn btn-primary">Modifier</a>
                        <a href="del.php?id=<?php echo urlencode($theme['id']); ?>" class="btn btn-danger">Supprimer</a>
                        <a href="artc.php?id=<?php echo urlencode($theme['id']); ?>" class="btn btn-primary">
                          Gérer les articles 
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
