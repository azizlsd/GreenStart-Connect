<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';

// Fetch all users for the reservation form dropdown
try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT id_user, nom_user FROM users ORDER BY nom_user");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $users = [];
}

// Récupérer la recherche, le critère de tri et le critère de recherche depuis les paramètres GET
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchColumn = isset($_GET['searchColumn']) ? $_GET['searchColumn'] : 'titre_event';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id_event';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

// Validate search/sort columns to prevent SQL injection
$allowedColumns = ['titre_event', 'description_event', 'localisation', 'id_event', 'date_debut', 'date_fin'];
if (!in_array($searchColumn, $allowedColumns)) {
    $searchColumn = 'titre_event';
}
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'id_event';
}

// Rechercher et trier les événements
$events = EventController::getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Handle event deletion
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    EventController::handleDeleteEvent($id);
    header("Location: eventList.php?success=" . urlencode("Événement supprimé avec succès"));
    exit;
}

// Handle reservation submission
$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        // Validate input
        $data = [
            'id_event' => $_POST['id_event'] ?? '',
            'id_user' => $_POST['id_user'] ?? '',
            'accom_res' => $_POST['accom_res'] ?? 0
        ];

        if (!is_numeric($data['id_event']) || !is_numeric($data['id_user']) || !is_numeric($data['accom_res']) || $data['accom_res'] < 0) {
            throw new Exception('Invalid input data');
        }

        // Fetch event details
        $eventStmt = $pdo->prepare("SELECT max_participants FROM events WHERE id_event = :id_event");
        $eventStmt->execute([':id_event' => $data['id_event']]);
        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
        if (!$event) {
            throw new Exception('Event not found');
        }

        // Check current reservations
        $resStmt = $pdo->prepare("SELECT SUM(accom_res + 1) as total FROM reservations WHERE id_event = :id_event");
        $resStmt->execute([':id_event' => $data['id_event']]);
        $totalParticipants = $resStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        $newParticipants = $data['accom_res'] + 1; // User + accompanists

        if ($totalParticipants + $newParticipants > $event['max_participants']) {
            throw new Exception('Maximum participants exceeded');
        }

        // Fetch nom_user from users table
        $userStmt = $pdo->prepare("SELECT nom_user FROM users WHERE id_user = :id_user");
        $userStmt->execute([':id_user' => $data['id_user']]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception('User not found');
        }

        // Insert reservation
        $query = "INSERT INTO reservations (id_event, id_user, nom_user, accom_res)
                  VALUES (:id_event, :id_user, :nom_user, :accom_res)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_event' => $data['id_event'],
            ':id_user' => $data['id_user'],
            ':nom_user' => $user['nom_user'],
            ':accom_res' => $data['accom_res']
        ]);

        $successMessage = 'Réservation effectuée avec succès!';
    } catch (Exception $e) {
        $errorMessage = 'Erreur lors de la réservation : ' . htmlspecialchars($e->getMessage());
        error_log('Reservation error: ' . $e->getMessage());
    }
}

// Check for success message from deletion
if (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
}

?>

<?php require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
 ?>

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Liste des évènements</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de recherche -->
                <form method="GET" action="eventList.php" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher un événement" value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="searchColumn" class="form-control">
                                <option value="titre_event" <?= $searchColumn === 'titre_event' ? 'selected' : '' ?>>Titre</option>
                                <option value="description_event" <?= $searchColumn === 'description_event' ? 'selected' : '' ?>>Description</option>
                                <option value="localisation" <?= $searchColumn === 'localisation' ? 'selected' : '' ?>>Lieu</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </div>
                    </div>
                </form>
                <a href="eventPDF.php?search=<?= urlencode($searchTerm) ?>&searchColumn=<?= urlencode($searchColumn) ?>&sort=<?= urlencode($sortColumn) ?>&order=<?= urlencode($sortOrder) ?>" class="btn btn-info">Exporter en PDF</a>
                

                <?php if (!empty($events)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><a href="?sort=id_event&order=<?= $sortColumn === 'id_event' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">ID</a></th>
                                <th><a href="?sort=titre_event&order=<?= $sortColumn === 'titre_event' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">Titre</a></th>
                                <th>Description</th>
                                <th>Lieu</th>
                                <th><a href="?sort=date_debut&order=<?= $sortColumn === 'date_debut' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">Date de début</a></th>
                                <th><a href="?sort=date_fin&order=<?= $sortColumn === 'date_fin' && $sortOrder === 'asc' ? 'desc' : 'asc' ?>&search=<?= urlencode($searchTerm) ?>&searchColumn=<?= $searchColumn ?>">Date de fin</a></th>
                                <th>Participants max.</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?= htmlspecialchars($event['id_event']) ?></td>
                                    <td><?= htmlspecialchars($event['titre_event']) ?></td>
                                    <td><?= htmlspecialchars($event['description_event']) ?></Nd>
                                    <td><?= htmlspecialchars($event['localisation']) ?></td>
                                    <td><?= htmlspecialchars($event['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($event['date_fin']) ?></td>
                                    <td><?= htmlspecialchars($event['max_participants']) ?></td>
                                    <td>
                                        <a href="eventForm.php?id=<?= $event['id_event'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                        <a href="?action=delete&id=<?= $event['id_event'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer</a>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="setEventId(<?= $event['id_event'] ?>)">Réserver</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun événement à afficher.</p>
                <?php endif; ?>

                <!-- Reservation Modal -->
                <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reservationModalLabel">Réserver un événement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="eventList.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="id_event" id="modalEventId">
                                    <div class="mb-3">
                                        <label for="id_user" class="form-label">Utilisateur</label>
                                        <select class="form-control" name="id_user" id="id_user" required>
                                            <option value="">Sélectionner un utilisateur</option>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nom_user']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="accom_res" class="form-label">Nombre d'accompagnants</label>
                                        <input type="number" class="form-control" name="accom_res" id="accom_res" min="0" value="0" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Valider</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<script>
function setEventId(eventId) {
    document.getElementById('modalEventId').value = eventId;
}
</script>

<?php require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php'; ?>