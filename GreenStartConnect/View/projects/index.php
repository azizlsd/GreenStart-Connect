
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Projets</h3>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_GET['success'])) {
                        $message = '';
                        switch ($_GET['success']) {
                            case 'create':
                                $message = 'Le projet a été créé avec succès.';
                                break;
                            case 'update':
                                $message = 'Le projet a été modifié avec succès.';
                                break;
                            case 'delete':
                                $message = 'Le projet a été supprimé avec succès.';
                                break;
                        }
                        if ($message): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif;
                    }

                    if (isset($_GET['error'])) {
                        $message = '';
                        switch ($_GET['error']) {
                            case 'notfound':
                                $message = 'Le projet demandé n\'existe pas.';
                                break;
                            case 'delete':
                                $message = 'Une erreur est survenue lors de la suppression du projet.';
                                break;
                        }
                        if ($message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif;
                    }
                    ?>

                    <div class="table-responsive">
                        <div class="mb-3 d-flex gap-3">
                            <div class="input-group" style="max-width: 400px;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un projet par titre...">
                                <button class="btn btn-primary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <button id="sortBudgetBtn" class="btn btn-outline-primary">
                                <i class="fas fa-sort-amount-down me-2"></i>Trier par budget
                            </button>
                            <a href="index.php?controller=projectExport&action=exportPDF" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
                            </a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Statut</th>
                                    <th>Budget</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($projects) && !empty($projects)): ?>
                                    <?php foreach ($projects as $project): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($project['start_date'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($project['end_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $project['status'] === 'En cours' ? 'primary' : 
                                                        ($project['status'] === 'Terminé' ? 'success' : 'warning'); 
                                                ?>">
                                                    <?php echo htmlspecialchars($project['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($project['budget'], 2, ',', ' '); ?> €</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="index.php?controller=project&action=edit&id=<?php echo $project['id']; ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?php echo $project['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun projet trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php?controller=project&action=create" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Ajouter un nouveau projet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
        window.location.href = 'index.php?controller=project&action=delete&id=' + id;
    }
}

// Fonction pour rechercher les projets par titre
document.getElementById('searchBtn').addEventListener('click', function() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    let found = false;

    rows.forEach(row => {
        const title = row.querySelector('td:first-child').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            row.style.display = '';
            found = true;
        } else {
            row.style.display = 'none';
        }
    });

    // Afficher un message si aucun résultat n'est trouvé
    const noResultsRow = document.querySelector('tbody tr td[colspan="7"]');
    if (noResultsRow) {
        noResultsRow.parentElement.style.display = found ? 'none' : '';
    }

    // Afficher une notification si aucun résultat n'est trouvé
    let notification = document.getElementById('searchNotification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'searchNotification';
        notification.className = 'alert alert-info mt-3';
        notification.style.display = 'none';
        document.querySelector('.table-responsive').insertAdjacentElement('afterend', notification);
    }

    if (!found && searchTerm !== '') {
        notification.textContent = `Aucun projet trouvé avec le titre "${searchTerm}"`;
        notification.style.display = '';
    } else {
        notification.style.display = 'none';
    }
});

// Permettre la recherche en appuyant sur Entrée
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});

// Réinitialiser la recherche quand le champ est vidé
document.getElementById('searchInput').addEventListener('input', function() {
    if (this.value === '') {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => row.style.display = '');
        document.getElementById('searchNotification').style.display = 'none';
    }
});

// Fonction pour trier les projets par budget
document.getElementById('sortBudgetBtn').addEventListener('click', function() {
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    let isAscending = this.classList.contains('asc');

    // Trier les lignes
    rows.sort((a, b) => {
        const budgetA = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace(/[^0-9,]/g, '').replace(',', '.'));
        const budgetB = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace(/[^0-9,]/g, '').replace(',', '.'));
        return isAscending ? budgetA - budgetB : budgetB - budgetA;
    });

    // Réorganiser les lignes dans le tableau
    rows.forEach(row => tbody.appendChild(row));

    // Mettre à jour l'état du bouton
    this.classList.toggle('asc');
    this.innerHTML = isAscending ? 
        '<i class="fas fa-sort-amount-down me-2"></i>Trier par budget' : 
        '<i class="fas fa-sort-amount-up me-2"></i>Trier par budget';
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