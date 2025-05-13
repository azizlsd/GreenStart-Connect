<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->


<head>
    <meta charset="utf-8">
    <title>Dashboard - GreenStart Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords"
    content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Libraries Stylesheet -->
    <link href="/GreenStartConnect/GreenStartConnect/Extra/ExtraFront/lib/animate/animate.min.css" rel="stylesheet">
    
    <!-- Customized Bootstrap Stylesheet -->
    <link href="/GreenStartConnect/GreenStartConnect/Extra/ExtraFront/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Template Stylesheet -->
    <link href="/GreenStartConnect/GreenStartConnect/Extra/ExtraFront/css/style.css" rel="stylesheet">
    <link href="/GreenStartConnect/GreenStartConnect/assets/css/dashboard-style.css" rel="stylesheet">
       <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords"
    content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png"
    type="image/x-icon">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet"
    href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style.css"
    id="main-style-link">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style-preset.css">
  <!-- [Page specific CSS] start -->
  <link rel="stylesheet"
    href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/plugins/datepicker-bs5.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  <!-- [ Sidebar Menu ] start -->
  <?php include __DIR__ . '/../BackOffice/layouts/navbar.php'; ?>
  <!-- [ Sidebar Menu ] end -->
  <?php include __DIR__ . '/../BackOffice/layouts/header.php'; ?>
  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
    <!-- Main Content Start -->
    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-gradient p-3 rounded">
                                    <i class="fas fa-project-diagram fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Projets Totaux</h5>
                                <h3 class="mb-0"><?php echo $totalProjects; ?></h3>
                                <small class="text-muted">Projets en cours et terminés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-gradient p-3 rounded">
                                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Événements</h5>
                                <h3 class="mb-0"><?php echo $totalEvents; ?></h3>
                                <small class="text-muted">Événements planifiés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-gradient p-3 rounded">
                                    <i class="fas fa-coins fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Budget Total</h5>
                                <h3 class="mb-0"><?php echo number_format($totalBudget, 2); ?> €</h3>
                                <small class="text-muted">Investissement total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-gradient p-3 rounded">
                                    <i class="fas fa-tasks fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Projets Actifs</h5>
                                <h3 class="mb-0"><?php echo $activeProjects; ?></h3>
                                <small class="text-muted">En cours de réalisation</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary bg-gradient p-3 rounded">
                                    <i class="fas fa-user-check fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">Postulations Totales</h5>
                                <h3 class="mb-0"><?php echo $totalPostulations; ?></h3>
                                <small class="text-muted">Toutes les postulations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Évolution des Projets</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="projectsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statuts des Projets</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Projets Récents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Statut</th>
                                <th>Budget</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentProjects as $project): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $project['status'] === 'En cours' ? 'success' : 
                                            ($project['status'] === 'En attente' ? 'warning' : 'secondary'); 
                                    ?>">
                                        <?php echo htmlspecialchars($project['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($project['budget'], 2); ?> €</td>
                                <td><?php echo date('d/m/Y', strtotime($project['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?controller=project&action=edit&id=<?php echo $project['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Postulations Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Postulations Récentes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Projet</th>
                                <th>Feedback</th>
                                <th>Date de création</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPostulations as $postulation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($postulation['nom']); ?></td>
                                <td><?php echo htmlspecialchars($postulation['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($postulation['project_title']); ?></td>
                                <td><?php echo htmlspecialchars(mb_strimwidth($postulation['feedback'], 0, 30, '...')); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($postulation['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Évolution des Postulations</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="postulationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statuts des Postulations</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="postulationStatusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statuts des Postulations par Projet -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statuts des Postulations par Projet</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>En attente</th>
                                <th>Acceptée</th>
                                <th>Refusée</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($postulationStatusByProject as $project => $statuses): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($project); ?></td>
                                <td><?php echo $statuses['En attente'] ?? 0; ?></td>
                                <td><?php echo $statuses['Acceptée'] ?? 0; ?></td>
                                <td><?php echo $statuses['Refusée'] ?? 0; ?></td>
                                <td><?php echo array_sum($statuses); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content End -->

    <!-- Charts JavaScript -->
    <script>
        // Données pour le graphique d'évolution des projets
        const projectsData = {
            labels: <?php echo json_encode(array_keys($projectsOverTime)); ?>,
            datasets: [{
                label: 'Nombre de Projets',
                data: <?php echo json_encode(array_values($projectsOverTime)); ?>,
                fill: true,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4
            }]
        };

        // Données pour le graphique circulaire des statuts
        const statusData = {
            labels: <?php echo json_encode(array_keys($projectsByStatus)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($projectsByStatus)); ?>,
                backgroundColor: [
                    '#198754',  // success
                    '#ffc107',  // warning
                    '#6c757d'   // secondary
                ]
            }]
        };

        // Données pour le graphique d'évolution des postulations
        const postulationsData = {
            labels: <?php echo json_encode(array_keys($postulationsOverTime)); ?>,
            datasets: [{
                label: 'Nombre de Postulations',
                data: <?php echo json_encode(array_values($postulationsOverTime)); ?>,
                fill: true,
                borderColor: '#6c757d',
                backgroundColor: 'rgba(108, 117, 125, 0.1)',
                tension: 0.4
            }]
        };

        // Données pour le graphique circulaire des statuts de postulation
        const postulationStatusData = {
            labels: <?php echo json_encode(array_keys($postulationsByStatus)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($postulationsByStatus)); ?>,
                backgroundColor: [
                    '#198754',  // success
                    '#ffc107',  // warning
                    '#6c757d'   // secondary
                ]
            }]
        };

        // Configuration des graphiques
        window.addEventListener('load', function() {
            // Graphique d'évolution
            new Chart(document.getElementById('projectsChart'), {
                type: 'line',
                data: projectsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Graphique circulaire
            new Chart(document.getElementById('statusPieChart'), {
                type: 'doughnut',
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Graphique d'évolution des postulations
            new Chart(document.getElementById('postulationsChart'), {
                type: 'line',
                data: postulationsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Graphique circulaire des statuts de postulation
            new Chart(document.getElementById('postulationStatusPieChart'), {
                type: 'doughnut',
                data: postulationStatusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</div>
  </div>
  <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">GreenStart Connect &#9829; crafted by WeBoo

        </div>
        <div class="col-auto my-1">
          <ul class="list-inline footer-link mb-0">
            <li class="list-inline-item"><a href="../index.html">Home</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer> <!-- Required Js -->
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/popper.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/simplebar.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/bootstrap.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/fonts/custom-font.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/pcoded.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/feather.min.js"></script>





  <script>layout_change('light');</script>




  <script>change_box_container('false');</script>



  <script>layout_rtl_change('false');</script>


  <script>preset_change("preset-1");</script>


  <script>font_change("Public-Sans");</script>




</body>
<!-- [Body] end -->

</html>