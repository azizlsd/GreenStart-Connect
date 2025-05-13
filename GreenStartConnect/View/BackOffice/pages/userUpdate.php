<?php
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Adresse email invalide.";
  }

  if (empty($errors)) {
    $client = new Client();
    $client->update($_GET['id'], $_POST);
    header("Location: index.php?action=dashboard");
    exit();
  }
}
?>
<?php if (!empty($errors)): ?>
  <div class="d-flex justify-content-center">
    <div class="alert alert-danger w-75 text-center">
      <?php foreach ($errors as $err): ?>
        <p>
          <?= htmlspecialchars($err) ?>
        </p>
      <?php endforeach; ?>
    </div>
  </div>
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
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <!-- ========   Change your logo from here   ============ -->
        <a href="#"><img src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png"
            alt="img" width="160" height="auto"></a>

      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item">
            <a href="../dashboard/index.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>UI Components</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item">
            <a href="../elements/bc_typography.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-typography"></i></span>
              <span class="pc-mtext">Typography</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="../elements/bc_color.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
              <span class="pc-mtext">Color</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="../elements/icon-tabler.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-plant-2"></i></span>
              <span class="pc-mtext">Icons</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Pages</label>
            <i class="ti ti-news"></i>
          </li>
          <li class="pc-item">
            <a href="../pages/login.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-lock"></i></span>
              <span class="pc-mtext">Login</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="../pages/register.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
              <span class="pc-mtext">Register</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="../pages/event.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-planet"></i></span>
              <span class="pc-mtext">Evenements</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Other</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item pc-hasmenu">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-menu"></i></span><span
                class="pc-mtext">Menu
                levels</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item"><a class="pc-link" href="#!">Level 2.1</a></li>
              <li class="pc-item pc-hasmenu">
                <a href="#!" class="pc-link">Level 2.2<span class="pc-arrow"><i
                      data-feather="chevron-right"></i></span></a>
                <ul class="pc-submenu">
                  <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                  <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                  <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i
                          data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                      <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                      <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class="pc-item pc-hasmenu">
                <a href="#!" class="pc-link">Level 2.3<span class="pc-arrow"><i
                      data-feather="chevron-right"></i></span></a>
                <ul class="pc-submenu">
                  <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                  <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                  <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">Level 3.3<span class="pc-arrow"><i
                          data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                      <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                      <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="pc-item">
            <a href="../other/sample-page.html" class="pc-link">
              <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
              <span class="pc-mtext">Sample page</span>
            </a>
          </li>
        </ul>
        <div class="card text-center">
          <div class="card-body">
            <img src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/img-navbar-card.png"
              alt="images" class="img-fluid mb-2">
            <h5>Upgrade To Pro</h5>
            <p>To get more features and components</p>
            <a href="https://codedthemes.com/item/berry-bootstrap-5-admin-template/" target="_blank"
              class="btn btn-success">Buy Now</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end -->
  <!-- [ Header Topbar ] start -->
  <header class="pc-header">
    <div class="header-wrapper">
      <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-search"></i>
            </a>
            <div class="dropdown-menu pc-h-dropdown drp-search">
              <form class="px-3">
                <div class="form-group mb-0 d-flex align-items-center">
                  <i data-feather="search"></i>
                  <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                </div>
              </form>
            </div>
          </li>
          <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
              <i data-feather="search" class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search here. . .">
            </form>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-mail"></i>
            </a>
            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Message</h5>
                <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
              </div>
              <div class="dropdown-divider"></div>
              <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                style="max-height: calc(100vh - 215px)">
                <div class="list-group list-group-flush w-100">
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img
                          src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-2.jpg"
                          alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">3:00 AM</span>
                        <p class="text-body mb-1">It's <b>Cristina danny's</b> birthday today.</p>
                        <span class="text-muted">2 min ago</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img
                          src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-1.jpg"
                          alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">6:00 PM</span>
                        <p class="text-body mb-1"><b>Aida Burg</b> commented your post.</p>
                        <span class="text-muted">5 August</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img
                          src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-3.jpg"
                          alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">2:45 PM</span>
                        <p class="text-body mb-1"><b>There was a failure to your setup.</b></p>
                        <span class="text-muted">7 hours ago</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img
                          src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-4.jpg"
                          alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">9:10 PM</span>
                        <p class="text-body mb-1"><b>Cristina Danny </b> invited to join <b> Meeting.</b></p>
                        <span class="text-muted">Daily scrum meeting time</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <div class="text-center py-2">
                <a href="#!" class="link-primary">View all</a>
              </div>
            </div>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <img src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-2.jpg"
                alt="user-image" class="user-avtar">
              <span>Stebin Ben</span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header">
                <div class="d-flex mb-1">
                  <div class="flex-shrink-0">
                    <img
                      src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/user/avatar-2.jpg"
                      alt="user-image" class="user-avtar wid-35">
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Stebin Ben</h6>
                    <span>UI/UX Designer</span>
                  </div>
                  <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
                </div>
              </div>
              <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="drp-t1" data-bs-toggle="tab" data-bs-target="#drp-tab-1"
                    type="button" role="tab" aria-controls="drp-tab-1" aria-selected="true"><i class="ti ti-user"></i>
                    Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="drp-t2" data-bs-toggle="tab" data-bs-target="#drp-tab-2" type="button"
                    role="tab" aria-controls="drp-tab-2" aria-selected="false"><i class="ti ti-settings"></i>
                    Setting</button>
                </li>
              </ul>
              <div class="tab-content" id="mysrpTabContent">
                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1"
                  tabindex="0">
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-edit-circle"></i>
                    <span>Edit Profile</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-user"></i>
                    <span>View Profile</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-clipboard-list"></i>
                    <span>Social Profile</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-wallet"></i>
                    <span>Billing</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-power"></i>
                    <span>Logout</span>
                  </a>
                </div>
                <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2" tabindex="0">
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-help"></i>
                    <span>Support</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-user"></i>
                    <span>Account Settings</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-lock"></i>
                    <span>Privacy Center</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-messages"></i>
                    <span>Feedback</span>
                  </a>
                  <a href="#!" class="dropdown-item">
                    <i class="ti ti-list"></i>
                    <span>History</span>
                  </a>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <!-- [ Header ] end -->



  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Modifier Utilisateur</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Liste des utilisateurs</a></li>
                <li class="breadcrumb-item" aria-current="page">Modifier</li>
              </ul>
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
            <div class="card-header">
              <h5>Modifier utilisateur</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="index.php?action=edit&id=<?= urlencode($data['id'] ?? '') ?>">
                <div class="mb-3">
                  <label>Nom</label>
                  <input type="text" name="nom" class="form-control" value="<?= $data['nom'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Prénom</label>
                  <input type="text" name="prenom" class="form-control" value="<?= $data['prenom'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <input type="text" id="email" name="email" class="form-control" value="<?= $data['email'] ?>"
                    required>
                </div>
                <div class="mb-3">
                  <label>Téléphone</label>
                  <input type="text" name="telephone" class="form-control" value="<?= $data['telephone'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Adresse</label>
                  <input type="text" name="adresse" class="form-control" value="<?= $data['adresse'] ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Mettre à jour</button>
              </form>

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
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.querySelector('form');
      const emailInput = document.getElementById('email'); // Assure-toi que cet ID existe

      form.addEventListener('submit', function (e) {
        const emailValue = emailInput.value.trim();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(emailValue)) {
          e.preventDefault(); // Bloquer le formulaire
          alert("❌ Adresse email invalide ! Exemple : exemple@mail.com");
        }
      });
    });
  </script>
  <script src="//code.tidio.co/p1wwyyavs8kh2wabxcl7h78pfgtcnycl.js" async></script>



</body>
<!-- [Body] end -->

</html>