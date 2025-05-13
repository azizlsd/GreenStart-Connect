<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>GreenStart Connect</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link rel="icon" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

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


    <?php include __DIR__ . '/navbar.php'; ?>


    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Type search keyword">
                        <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->



    

    
<!-- Freelancer Data CRUD Section -->
<div class="container my-5">
    <!-- Tableau d'affichage des données -->
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-user me-2"></i>Mes Informations Personnelles</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Champ</th>
                        <th>Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nom</strong></td>
                        <td id="displayLastname"><?= htmlspecialchars($_SESSION['client']['nom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Prénom</strong></td>
                        <td id="displayFirstname"><?= htmlspecialchars($_SESSION['client']['prenom'] ?? '') ?>></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td id="displayEmail"><?= htmlspecialchars($_SESSION['client']['email'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Téléphone</strong></td>
                        <td id="displayPhone"><?= htmlspecialchars($_SESSION['client']['telephone'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Adresse</strong></td>
                        <td id="displayAddress"><?= htmlspecialchars($_SESSION['client']['adresse'] ?? '') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-sm btn-outline-primary" id="toggleEditForm">
                <i class="fas fa-edit me-1"></i>Modifier
            </button>
        </div>
    </div>

    <!-- Formulaire de modification (masqué par défaut) -->
    <div class="bg-light rounded p-5" id="editFormContainer" style="display: none;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier mes informations</h3>
        </div>
        <form id="freelancerProfileForm" action="index.php?action=editUser&id=<?= $_SESSION['client']['id'] ?>" method="POST">
      
            
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control bg-white border-0" name="nom" 
                    value=" <?= htmlspecialchars($_SESSION['client']['nom'] ?? '')  ?>" style="height: 55px;" required>
                </div>
                
                <div class="col-12 col-sm-6">
                    <label class="form-label">Prénom *</label>
                    <input type="text" class="form-control bg-white border-0" name="prenom" 
                           value="<?= htmlspecialchars($_SESSION['client']['prenom'] ?? '') ?>" style="height: 55px;" required>
                </div>
                
                <div class="col-12 col-sm-6">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control bg-white border-0" name="email" 
                           value="<?= htmlspecialchars($_SESSION['client']['email'] ?? '') ?>" style="height: 55px;" required>
                </div>
                
                <div class="col-12 col-sm-6">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control bg-white border-0" name="telephone" 
                           value="<?= htmlspecialchars($_SESSION['client']['telephone'] ?? '') ?>" style="height: 55px;" >
                    <small class="text-muted">Format : 0612345678</small>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Adresse</label>
                    <textarea class="form-control bg-white border-0" name="adresse" rows="3"><?= htmlspecialchars($_SESSION['client']['adresse'] ?? '') ?></textarea>
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                    <button type="button" class="btn btn-outline-secondary w-100 mt-2 py-3" id="cancelEdit">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script de gestion -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleEditForm');
    const editForm = document.getElementById('editFormContainer');
    const cancelBtn = document.getElementById('cancelEdit');
    
    // Basculer l'affichage du formulaire
    toggleBtn.addEventListener('click', function() {
        editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
    });
    
    // Annuler l'édition
    cancelBtn.addEventListener('click', function() {
        editForm.style.display = 'none';
    });
    
    // Mise à jour dynamique après soumission (simulé)
 
});
</script>
                
<?php include __DIR__ . '/footer.html'; ?>


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/wow/wow.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/easing/easing.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/waypoints/waypoints.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/counterup/counterup.min.js"></script>
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/lib/owlcarousel/owl.carousel.min.js"></script>

  

    <!-- Template Javascript -->
    <script src="/GreenStart-Connect-main/GreenStartConnect/View/FrontOffice/assets/js/main.js"></script>
</body>

</html>