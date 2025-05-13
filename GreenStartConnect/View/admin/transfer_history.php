
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Startup - Startup Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->



<?php include __DIR__ . '/../FrontOffice/navbar.php'; ?>
<div class="container mt-4">
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            foreach ($_SESSION['errors'] as $error) {
                echo htmlspecialchars($error);
            }
            unset($_SESSION['errors']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                Historique des Transferts
            </h5>
            <div>
                <a href="index.php?controller=balance&action=index" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la Balance
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-wallet me-2"></i>
                Solde actuel : <strong><?php echo number_format($totalBudget, 2, ',', ' '); ?> €</strong>
            </div>

            <?php if (isset($count)): ?>
            <div class="alert alert-secondary">
                <i class="fas fa-exchange-alt me-2"></i>
                Nombre total de transferts : <strong><?php echo $count; ?></strong>
            </div>
            <?php endif; ?>
            
            <!-- Search Form -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="transfersTable">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>RIB</th>
                            <th>Montant</th>
                            <th>Description</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transfers)): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun transfert n'a été effectué.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transfers as $transfer): ?>
                                <tr>
                                    <td>
                                        <i class="far fa-calendar-alt me-2"></i>
                                        <?php 
                                        if (isset($transfer['created_at']) && !empty($transfer['created_at'])) {
                                            echo date('d/m/Y H:i', strtotime($transfer['created_at']));
                                        } else {
                                            echo '<span class="text-muted">Date non disponible</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-university me-2"></i>
                                        <?php echo chunk_split($transfer['rib'], 4, ' '); ?>
                                    </td>
                                    <td class="text-danger">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        -<?php echo number_format($transfer['montant'], 2, ',', ' '); ?> €
                                    </td>
                                    <td>
                                        <i class="fas fa-comment-alt me-2"></i>
                                        <?php echo htmlspecialchars($transfer['description']); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Effectué
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Debug: Afficher les données brutes -->
            <?php if (!empty($transfers) && isset($_GET['debug'])): ?>
            <div class="mt-4">
                <details>
                    <summary class="text-muted">
                        <i class="fas fa-bug me-2"></i>
                        Données de débogage
                    </summary>
                    <pre class="mt-2 p-3 bg-light"><?php print_r($transfers); ?></pre>
                </details>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Search Script -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var table = document.getElementById('transfersTable');
    var rows = table.getElementsByTagName('tr');

    for (var i = 1; i < rows.length; i++) {
        var show = false;
        var cells = rows[i].getElementsByTagName('td');
        
        for (var j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(input) > -1) {
                show = true;
                break;
            }
        }
        
        rows[i].style.display = show ? '' : 'none';
    }
});
</script>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="index.html" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>greenstart</h1>
                        </a>
                        <p class="mt-3 mb-4">Lorem diam sit erat dolor elitr et, diam lorem justo amet clita stet eos sit. Elitr dolor duo lorem, elitr clita ipsum sea. Diam amet erat lorem stet eos. Diam amet et kasd eos duo.</p>
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                                <button class="btn btn-dark">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Get In Touch</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Street, New York, USA</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">info@example.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+012 345 67890</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Quick Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Popular Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                                <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                                <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Your Site Name</a>. All Rights Reserved. 
						
						<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
						Designed by <a class="text-white border-bottom" href="https://htmlcodex.com">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


   <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/wow/wow.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/easing/easing.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/waypoints/waypoints.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/counterup/counterup.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/owlcarousel/owl.carousel.min.js"></script>

    <script src="//code.tidio.co/uzrsftyakoywwjasq6wovilf1gfw1qko.js" async></script>

    <!-- Template Javascript -->
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/js/main.js"></script>

</body>

</html>