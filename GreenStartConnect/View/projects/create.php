
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
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nouveau Projet</h5>
                </div>
                <div class="card-body">
                    <form id="projectForm" action="index.php?controller=project&action=create" method="POST" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Date de début</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Date de fin</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Sélectionnez un statut</option>
                                <option value="En cours">En cours</option>
                                <option value="Terminé">Terminé</option>
                                <option value="En attente">En attente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="budget" class="form-label">Budget (€)</label>
                            <input type="number" class="form-control" id="budget" name="budget">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=project&action=index" class="btn btn-secondary">Retour</a>
                            <button type="submit" class="btn btn-success">Créer le projet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const title = document.getElementById('title');
    const description = document.getElementById('description');
    const start_date = document.getElementById('start_date');
    const end_date = document.getElementById('end_date');
    const status = document.getElementById('status');
    const budget = document.getElementById('budget');

    // Validation du titre
    if (title.value.trim() === '') {
        alert('Veuillez saisir le titre du projet');
        title.focus();
        return false;
    }

    if (title.value.trim().length < 5) {
        alert('Le titre doit contenir au moins 5 caractères');
        title.focus();
        return false;
    }

    // Validation de la description
    if (description.value.trim() === '') {
        alert('Veuillez saisir la description du projet');
        description.focus();
        return false;
    }

    if (description.value.trim().length < 20) {
        alert('La description doit contenir au moins 20 caractères');
        description.focus();
        return false;
    }

    // Validation de la date de début
    if (start_date.value === '') {
        alert('Veuillez sélectionner une date de début');
        start_date.focus();
        return false;
    }

    // Validation de la date de fin
    if (end_date.value === '') {
        alert('Veuillez sélectionner une date de fin');
        end_date.focus();
        return false;
    }

    // Vérifier que la date de fin est après la date de début
    if (new Date(end_date.value) <= new Date(start_date.value)) {
        alert('La date de fin doit être postérieure à la date de début');
        end_date.focus();
        return false;
    }

    // Validation du statut
    if (status.value === '') {
        alert('Veuillez sélectionner un statut');
        status.focus();
        return false;
    }

    // Validation du budget
    if (budget.value === '') {
        alert('Veuillez saisir le budget');
        budget.focus();
        return false;
    }

    if (isNaN(budget.value) || budget.value <= 0) {
        alert('Le budget doit être un nombre positif');
        budget.focus();
        return false;
    }

    // Si tout est valide, demander confirmation
    if (confirm('Êtes-vous sûr de vouloir créer ce projet ?')) {
        return true;
    }
    return false;
}
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