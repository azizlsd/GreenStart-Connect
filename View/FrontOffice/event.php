<?php
require_once  'C:\xampp\htdocs\GreenStartConnect\Controller/eventC.php';
require_once  'C:\xampp\htdocs\GreenStartConnect\config.php';

// Fetch all users for the dropdown
try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT id_user, nom_user FROM users ORDER BY nom_user");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $users = [];
}

// Get search, sort, and order parameters from GET
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchColumn = isset($_GET['searchColumn']) ? $_GET['searchColumn'] : 'titre_event';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id_event';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

// Fetch events with search and sort
$events = EventController::getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Handle event deletion
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    EventController::handleDeleteEvent($id);
    header("Location: event.php");
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
        $errorMessage = $e->getMessage();
    }
}

include   'C:\xampp\htdocs\GreenStartConnect\View/FrontOffice/headerFront.php';
?>

<div class="container mt-4">
    <h1 class="mb-4">Liste des Événements</h1>

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
    <form method="GET" action="event.php" class="mb-3">
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

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Titre <span id="sortIcon0">▲▼</span></th>
                <th onclick="sortTable(1)">Description <span id="sortIcon1">▲▼</span></th>
                <th onclick="sortTable(2)">Date début <span id="sortIcon2">▲▼</span></th>
                <th onclick="sortTable(3)">Date fin <span id="sortIcon3">▲▼</span></th>
                <th onclick="sortTable(4)">Lieu <span id="sortIcon4">▲▼</span></th>
                <th onclick="sortTable(5)">Max participants <span id="sortIcon5">▲▼</span></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="eventTable" data-sort="asc">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['titre_event']) ?></td>
                        <td><?= htmlspecialchars(substr($event['description_event'], 0, 50)) ?>...</td>
                        <td><?= date('Y-m-d', strtotime($event['date_debut'])) ?></td>
                        <td><?= date('Y-m-d', strtotime($event['date_fin'])) ?></td>
                        <td><?= htmlspecialchars($event['localisation']) ?></td>
                        <td><?= htmlspecialchars($event['max_participants']) ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="openPopup(<?= $event['id_event'] ?>)">Réserver</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucun événement trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal Form -->
    <div id="popupForm" style="display: none; position: fixed; top: 20%; left: 30%; width: 40%; background: white; padding: 20px; border: 1px solid #ccc; z-index: 999;">
        <h4>Réserver un événement</h4>
        <form method="POST" action="event.php">
            <input type="hidden" name="id_event" id="popupEventId">
            <input type="hidden" name="action" value="add">
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
            <button type="button" class="btn btn-secondary" onclick="closePopup()">Annuler</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
function openPopup(eventId) {
    document.getElementById('popupEventId').value = eventId;
    document.getElementById('popupForm').style.display = 'block';
}

function closePopup() {
    document.getElementById('popupForm').style.display = 'none';
}

// Sorting
let currentSort = { col: null, dir: 'asc' };

function sortTable(colIndex) {
    const tbody = document.getElementById("eventTable");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const isNumeric = colIndex === 5 || !isNaN(rows[0].cells[colIndex].innerText.trim());

    const direction = currentSort.col === colIndex && currentSort.dir === 'asc' ? 'desc' : 'asc';
    currentSort = { col: colIndex, dir: direction };

    rows.sort((a, b) => {
        let aText = a.cells[colIndex].innerText.trim();
        let bText = b.cells[colIndex].innerText.trim();

        if (isNumeric) {
            aText = parseFloat(aText) || 0;
            bText = parseFloat(bText) || 0;
            return direction === 'asc' ? aText - bText : bText - aText;
        } else {
            return direction === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);
        }
    });

    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));

    for (let i = 0; i <= 5; i++) {
        document.getElementById('sortIcon' + i).innerText = '▲▼';
    }
    document.getElementById('sortIcon' + colIndex).innerText = direction === 'asc' ? '▲' : '▼';
}
</script>

<?php include   'C:\xampp\htdocs\GreenStartConnect\View/FrontOffice/footerFront.php'; ?>