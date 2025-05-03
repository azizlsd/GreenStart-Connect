<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

// Récupérer toutes les réservations
$reservations = ReservationController::getAllReservations();

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    ReservationController::handleDeleteReservation($id);
    header("Location: resList.php"); // Rediriger pour éviter la resoumission
    exit;
}
?>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
?>

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
                        <th>ID Événement</th>
                        <th>ID Utilisateur</th>
                        <th>Nom de l'utilisateur</th>
                        <th>Nombre d'accompagnants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id_event']) ?></td>
                        <td><?= htmlspecialchars($reservation['id_user']) ?></td>
                        <td><?= htmlspecialchars($reservation['nom_user']) ?></td>
                        <td><?= htmlspecialchars($reservation['accom_res']) ?></td>
                        <td>
                            <a href="resForm.php?id=<?= $reservation['id_res'] ?>" class="btn btn-warning btn-sm">Modifier</a>
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

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?>
