<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';

// Paramètres de recherche et tri
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchColumn = isset($_GET['searchColumn']) ? $_GET['searchColumn'] : 'nom_user';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id_res';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

// Récupération des réservations filtrées
$reservations = ReservationController::getFilteredReservations($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Suppression d'une réservation
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    ReservationController::handleDeleteReservation($id);
    header("Location: resList.php?success=" . urlencode("Réservation supprimée avec succès"));
    exit;
}

// Messages
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
?>

<?php require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php'; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Liste des réservations</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de recherche -->
                <form method="GET" action="resList.php" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="searchColumn" class="form-control">
                                <option value="nom_user" <?= $searchColumn === 'nom_user' ? 'selected' : '' ?>>Nom utilisateur</option>
                                <option value="id_user" <?= $searchColumn === 'id_user' ? 'selected' : '' ?>>ID utilisateur</option>
                                <option value="id_event" <?= $searchColumn === 'id_event' ? 'selected' : '' ?>>ID événement</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                            <a href="resPDF.php?search=<?= urlencode($searchTerm) ?>&searchColumn=<?= urlencode($searchColumn) ?>&sort=<?= urlencode($sortColumn) ?>&order=<?= urlencode($sortOrder) ?>" class="btn btn-info">Exporter en PDF</a>
                        </div>
                    </div>
                </form>

                <?php if (!empty($reservations)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><a href="?sort=id_event&order=<?= $sortColumn === 'id_event' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">ID Événement</a></th>
                                <th><a href="?sort=id_user&order=<?= $sortColumn === 'id_user' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">ID Utilisateur</a></th>
                                <th><a href="?sort=nom_user&order=<?= $sortColumn === 'nom_user' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">Nom de l'utilisateur</a></th>
                                <th><a href="?sort=accom_res&order=<?= $sortColumn === 'accom_res' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">Accompagnants</a></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['id_event']) ?></td>
                                    <td><?= htmlspecialchars($res['id_user']) ?></td>
                                    <td><?= htmlspecialchars($res['nom_user']) ?></td>
                                    <td><?= htmlspecialchars($res['accom_res']) ?></td>
                                    <td>
                                        <!-- Modifier -->
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?= $res['id_res'] ?>" data-accom="<?= $res['accom_res'] ?>">Modifier</button>
                                        <!-- Supprimer -->
                                        <a href="?action=delete&id=<?= $res['id_res'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette réservation ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune réservation trouvée.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Accompagnants -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier le nombre d'accompagnants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="resForm.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="accom_res">Nombre d'accompagnants</label>
                        <input type="number" class="form-control" name="accom_res" id="accom_res" min="0" required>
                    </div>
                    <input type="hidden" name="id_res" id="id_res">
                    <input type="hidden" name="id_event" value="<?= htmlspecialchars($eventId) ?>"> <!-- Hidden event ID -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php'; ?>

<script>
    // JavaScript pour récupérer les données du bouton "Modifier" et les insérer dans le formulaire du modal
    const editButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id_res = this.getAttribute('data-id');
            const accom_res = this.getAttribute('data-accom');

            // Remplir les champs du modal avec les valeurs existantes
            document.getElementById('id_res').value = id_res;
            document.getElementById('accom_res').value = accom_res;
        });
    });
</script>
