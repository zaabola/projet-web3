
<?php
    // Database connection settings
    $host = "localhost";
    $dbname = "empreinte1";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Fetch all products for the dropdown and table
    $products = [];
    try {
        $stmt = $pdo->query("SELECT id_produit, Nom_Produit, Qte FROM produit");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error fetching products: " . $e->getMessage() . "</p>";
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_produit = $_POST['Nom_Produit'] ?? null;
        $new_quantity = $_POST['Qte'] ?? null;

        // Validate inputs
        if (!$nom_produit || $new_quantity === null || $new_quantity < 0) {
            echo "<p style='color: red;'>Invalid input. Please provide a valid product name and quantity.</p>";
        } else {
            try {
                // Update product quantity
                $update_sql = "UPDATE produit SET Qte = :new_quantity WHERE Nom_Produit = :nom_produit";
                $stmt = $pdo->prepare($update_sql);
                $stmt->execute([
                    'new_quantity' => $new_quantity,
                    'nom_produit' => $nom_produit
                ]);

                echo "<p style='color: green;'>Quantity updated successfully for product: " . htmlspecialchars($nom_produit) . "</p>";

                // Refresh the product list
                $stmt = $pdo->query("SELECT id_produit, Nom_Produit, Qte FROM produit");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "<p style='color: red;'>Error updating product: " . $e->getMessage() . "</p>";
            }
        }
    }
    ?>
<?php    
require_once 'c:/xampp/htdocs/projet/view/Backoffice/commande.php'; // Include the Commande class
require_once 'c:/xampp/htdocs/projet/config.php';  // Include your config for DB connection

// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=empreinte1", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Create an instance of the Commande class
$commande = new Commande($db);

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nom_client = $_POST['Nom_client'] ?? '';
    $Prenom_client = $_POST['Prenom_client'] ?? '';
    $Tel_client = $_POST['Tel_client'] ?? '';
    $Adresse_client = $_POST['Adresse_client'] ?? '';

    if (!empty($Nom_client) && !empty($Prenom_client) && strlen($Tel_client) === 8 && !empty($Adresse_client)) {
        if ($commande->createCommande($Adresse_client, $Tel_client, $Nom_client, $Prenom_client)) {
            $message = "Order created successfully!";
        } else {
            $message = "Failed to create order.";
        }
    } else {
        $message = "Please fill in all fields correctly.";
    }
}
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    body, html {
    background-color: white;
    color: black;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
}

.container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

h1, h2, h3, p {
    margin: 20px;
    color: black;
}

a {
    color: blue;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.chart-container {
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    margin: 20px;
}

  </style>
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

<body >
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Emprunt</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/dashboard.html">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="tables.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Orders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="deletecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">DeleteOrder</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="updatecommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">UpdateOrder</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="fetchcommande.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">fetchOrders</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/reclamation.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Complaints</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/produit.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Products</span>
          </a>
        </li>
        
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online Builder</a>
            </li>
            <li class="mt-1">
              <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
              </a>
            </li>
            <li class="nav-item dropdown pe-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">notifications</i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="../pages/sign-in.html" class="nav-link text-body font-weight-bold px-0">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">
            Check the sales, value and bounce rate by DT.
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          
        </div>
        
      <div class="row "  >
 

        <div  >
          <div class="card" >
            <div class="card-header pb-0" style=' width: 60vw; '>
              <div class="row">
               
                <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: white;
            color: black;
        }
        .dashboard-container {
            display: flex;
            flex-direction: column; /* Arrange the main container vertically */
            align-items: center;

        }
        .top-charts {
            display: flex;
            justify-content: space-around;
            width: 100%;
            margin-bottom: 40px; /* Add some space between the rows */
        }
        .chart-container {
            width: 45%; /* Adjusted width to make all charts bigger */
        }
        .chart-container-large {
            width: 80%; /* Adjusted width to make the total cost chart larger */
        }
        #pieChart, #barChart, #lineChart {
            max-width: 100%; /* Ensure the charts do not exceed the container width */
            height: 500px; /* Adjust height to balance sizes */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="dashboard-container">
        <div class="top-charts">
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <div class="chart-container-large">
            <canvas id="lineChart"></canvas>
        </div>
        <div class="col-md-6"> <div class="card"> <div class="card-body"> <h5 class="card-title">Total Benefits</h5> <p class="card-text" id="totalCostSummary"></p> </div> </div> </div>
    </div>

    <script>
      async function fetchTotalCostData() {
    try {
        const response = await fetch('fetch_total_cost.php'); // Ensure this path is correct based on your server setup
        const data = await response.json();
        console.log("Fetched Total Costs: ", data); // Debugging: log the fetched data

        // Parse and return the costs
        return data.map(item => parseFloat(item.total_cost));
    } catch (error) {
        console.error('Error fetching total cost data:', error);
        return [];
    }
}

function calculateTotalSum(totalCosts) {
    // Calculate the total sum of all benefits
    const sum = totalCosts.reduce((acc, curr) => acc + curr, 0);
    console.log("Calculated Total Sum: ", sum); // Debugging: log the total sum
    return sum;
}

async function displayTotalCost() {
    const totalCosts = await fetchTotalCostData();
    const totalSum = calculateTotalSum(totalCosts);

    // Display total sum in the summary section
    document.getElementById('totalCostSummary').textContent = `Total Sum of All Benefits: ${totalSum.toFixed(2)} DT`;
}

displayTotalCost();


function calculateTotalSum(totalCosts) {
    // Ensure all values are parsed correctly
    const sum = totalCosts.reduce((acc, curr) => {
        const parsedValue = isNaN(curr) ? 0 : curr; // Handle potential NaN values
        return acc + parsedValue;
    }, 0);

    console.log("Calculated Total Sum: ", sum); // Debugging: log the total sum
    return sum;
}

async function displayTotalCost() {
    const totalCosts = await fetchTotalCostData();
    const totalSum = calculateTotalSum(totalCosts);

    // Display total sum in the summary section
    document.getElementById('totalCostSummary').textContent = `Total Sum of All Benefits: ${totalSum.toFixed(2)} DT`;
}

displayTotalCost();

        async function fetchPieChartData() {
            try {
                const response = await fetch('product_stock.php');
                const data = await response.json();
                console.log('Fetched Pie Chart Data:', data);
                return data;
            } catch (error) {
                console.error('Error fetching pie chart data:', error);
                return [];
            }
        }

        async function fetchBarChartData() {
            try {
                const response = await fetch('data.php');
                const data = await response.json();
                console.log('Fetched Bar Chart Data:', data);
                return data;
            } catch (error) {
                console.error('Error fetching bar chart data:', error);
                return {};
            }
        }

        async function fetchLineChartData() {
            try {
                const response = await fetch('total_cost_by_panier.php');
                const data = await response.json();
                console.log('Fetched Line Chart Data:', data);
                return data;
            } catch (error) {
                console.error('Error fetching line chart data:', error);
                return [];
            }
        }

        function createPieChart(data) {
            const ctx = document.getElementById('pieChart').getContext('2d');
            const labels = data.map(item => item.Nom_Produit);
            const values = data.map(item => item.Qte);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(199, 199, 199, 0.2)',
                            'rgba(83, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        function createBarChart(data) {
            const ctx = document.getElementById('barChart').getContext('2d');
            const labels = ['Commands', 'Reclamations'];
            const values = [data.command_count, data.reclamation_count];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Count',
                        data: values,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 99, 132, 0.5)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function createLineChart(data) {
            const ctx = document.getElementById('lineChart').getContext('2d');
            const labels = data.map(item => `Panier ${item.id_panier}`);
            const values = data.map(item => item.total_cost_sum);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Benifits',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Panier ID'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Benifits (DT)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    }
                }
            });
        }

        async function init() {
            const pieChartData = await fetchPieChartData();
            const barChartData = await fetchBarChartData();
            const lineChartData = await fetchLineChartData();
            createPieChart(pieChartData);
            createBarChart(barChartData);
            createLineChart(lineChartData);
        }

        init();
    </script>
</body>
</html>




                <div class="col-lg-6 col-5 my-auto text-end">
                  
                  <div class="dropdown float-lg-end pe-4">
                    
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>
</html>
