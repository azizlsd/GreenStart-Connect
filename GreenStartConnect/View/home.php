<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GreenStart Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="Extra/ExtraFront/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="Extra/ExtraFront/lib/animate/animate.min.css" rel="stylesheet">
    <link href="Extra/ExtraFront/css/bootstrap.min.css" rel="stylesheet">
    <link href="Extra/ExtraFront/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid bg-success px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Ariana Petite, Ariana</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+216 90 326 185</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>support@gsconnect.com</small>
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
            <a href="index.php" class="navbar-brand p-0">
                <img src="img/logo.png" alt="GreenStart Connect" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Accueil</a>
                    <a href="View/FrontOffice/event.php" class="nav-item nav-link">Evenement</a>
                    <a href="index.php?controller=project&action=index" class="nav-item nav-link">Gestion des Projets</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Content -->
    <?php include 'View/layout/content.php'; ?>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-success p-4">
                        <a href="index.php" class="navbar-brand">
                            <h1 class="m-0 text-white">GreenStart Connect</h1>
                        </a>
                        <p class="mt-3 mb-4">Ensemble pour un avenir plus vert et durable</p>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Contactez-nous</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-success me-2"></i>
                                <p class="mb-0">Ariana Petite, Ariana</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-success me-2"></i>
                                <p class="mb-0">support@gsconnect.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-success me-2"></i>
                                <p class="mb-0">+216 90 326 185</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Extra/ExtraFront/lib/wow/wow.min.js"></script>
    <script src="Extra/ExtraFront/lib/easing/easing.min.js"></script>
    <script src="Extra/ExtraFront/lib/waypoints/waypoints.min.js"></script>
    <script src="Extra/ExtraFront/lib/counterup/counterup.min.js"></script>
    <script src="Extra/ExtraFront/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="Extra/ExtraFront/js/main.js"></script>

    <script>
    // Masquer le spinner une fois que la page est chargée
    window.addEventListener('load', function () {
        const spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove('show');
        }
    });

    // Masquer le spinner après 2 secondes si la page n'est pas encore chargée
    setTimeout(function() {
        const spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove('show');
        }
    }, 2000);
    </script>
</body>
</html> 