    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Ariana Petite, Ariana</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 90 326 185</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>contact@gsconnect.com</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.html" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Greenstart Connect</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
             <a href="index.php?action=event" class="nav-item nav-link">Event</a>
<a href="index.php?action=Manageblog" class="nav-item nav-link">Blog</a>
<a href="index.php?controller=project&action=index" class="nav-item nav-link">Projects</a>
                    <a href="index.php?controller=postulation&action=index" class="nav-item nav-link">Postuler</a>
              
 <a href="index.php?controller=balance&action=index" class="nav-item nav-link">
                      Balance
                    </a>

<a href="index.php?action=profil" class="nav-item nav-link">Profile</a>
      <a href="index.php?controller=reclamation&action=index" class="nav-item nav-link">Réclamation</a>
<a href="index.php?action=feedbackuser" class="nav-item nav-link">Contact</a>

                 
                    
                </div>
                <butaton type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></butaton>
                <a href="index.php?action=logout" class="btn btn-primary py-2 px-4 ms-3">LogOut</a>
            </div>
        </nav>

        <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <?php
$pageTitle = 'Home'; // default

if (isset($_GET['controller'])) {
 switch ($_GET['controller']) {
        case 'reclamation':
            $pageTitle = 'reclamation';
            break;
        case 'balance':
            $pageTitle = 'balance';
            break;
            case 'project':
            $pageTitle = 'project';
            break;
              case 'postulation':
            $pageTitle = 'postulation';
            break;
        }
}
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'event':
            $pageTitle = 'Events';
            break;
        case 'Manageblog':
            $pageTitle = 'Blog';
            break;
        case 'projects':
            $pageTitle = 'Projects';
            break;
        case 'balance':
            $pageTitle = 'Balance';
            break;
        case 'offre':
            $pageTitle = 'Offre';
            break;
        case 'profil':
            $pageTitle = 'Profile';
            break;
        case 'feedbackuser':
            $pageTitle = 'Contact';
            break;
    }
}
?>
<h1 class="display-4 text-white animated zoomIn"><?= $pageTitle ?></h1>
                   
                </div>
            </div>
        </div>
    </div>
  
    <!-- Navbar End -->