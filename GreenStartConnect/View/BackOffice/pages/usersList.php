<?php if (isset($_SESSION['flash'])): ?>
  <div class="alert alert-success text-center">
    <?= $_SESSION['flash'] ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Evenements * GreenStart Connect Dashboard</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
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
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>
  <!-- [ Sidebar Menu ] end -->
  <?php include __DIR__ . '/../layouts/header.php'; ?>
  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="m-b-10">Liste des utilisateur</h2>
                <form method="GET" action="index.php" class="d-flex align-items-center gap-2  mt-3">
                  <input type="hidden" name="action" value="usersList">
                  <input type="text" name="search" class="form-control" placeholder="Rechercher un utilisateur..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                  <button type="submit" style="background-color: #2e8a56;" class="btn btn-primary">Rechercher</button>
                </form>



              </div>
             
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ Form Validation ] start -->
        <div class="col-sm-12">
          <div class="card">
           
            <div class="card-body">
              <!-- ✅ Statistics Chart (above the table) -->

              <table class="table table-bordered table-hover">
                <thead style="background-color: #2e8a56;">
                  <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($clients as $c): ?>
                    <tr>
                      <td>
                        <?= $c['nom'] . ' ' . $c['prenom'] ?>
                      </td>
                      <td>
                        <?= $c['email'] ?>
                      </td>
                      <td>
                        <?= $c['telephone'] ?>
                      </td>
                      <td>
                        <a href="index.php?action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="index.php?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger"
                          onclick="return confirm('Supprimer ce client ?')">Supprimer</a>

                        <?php if ($_SESSION['client']['role'] === 'admin'): ?>
                          <?php if ($c['banned']): ?>
                            <a href="index.php?action=unban&id=<?= $c['id'] ?>" class="btn btn-success btn-sm">Unban</a>
                          <?php else: ?>
                            <a href="index.php?action=ban&id=<?= $c['id'] ?>" class="btn btn-danger btn-sm">Ban</a>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                </tbody>
              </table>
              <div class="my-4" style="max-width: 700px; margin: auto;">
                <canvas id="clientStatsChart"></canvas>
              </div>

              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
              <script>
                const ctx = document.getElementById('clientStatsChart').getContext('2d');
                new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: ['Total Clients', 'Banned Users', 'Admins'],
                    datasets: [{
                      label: 'Nombre d\'utilisateurs',
                      data: [<?= $totalClients ?>, <?= $totalBanned ?>, <?= $totalAdmins ?>],
                      backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                      ],
                      borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                      ],
                      borderWidth: 1,
                      barThickness: 30
                    }]
                  },
                  options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                      legend: {
                        display: true,
                        labels: {
                          font: {
                            size: 12
                          }
                        }
                      }
                    },
                    scales: {
                      x: {
                        beginAtZero: true
                      }
                    }
                  }
                });
              </script>

            </div>
          </div>
        </div>
        <!-- [ Form Validation ] end -->
      </div>
      <!-- [ Main Content ] end -->
    </div>
    </section>
    <!-- [ Main Content ] end -->
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