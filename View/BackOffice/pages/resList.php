<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

// Fetch all reservations using the controller
$reservations = ReservationController::getAllReservations();

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    ReservationController::handleDeleteReservation($id);
    header("Location: reservationList.php"); // Rediriger pour éviter la resoumission
    exit;
}
?>s

<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';  // Include the header file at the top
?>

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Liste des réservations</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($reservations)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ID Événement</th>
                                <th>ID Utilisateur</th>
                                <th>Nom de l'utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['id_res']) ?></td>
                                <td><?= htmlspecialchars($reservation['id_event']) ?></td>
                                <td><?= htmlspecialchars($reservation['id_user']) ?></td>
                                <td><?= htmlspecialchars($reservation['nom_user']) ?></td>
                                <td>
                                    <a href="reservationForm.php?id=<?= $reservation['id_res'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                    <a href="?action=delete&id=<?= $reservation['id_res'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune réservation à afficher.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';  // Include the footer
?>
