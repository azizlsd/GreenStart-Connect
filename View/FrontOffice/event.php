<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

$events = EventController::getAllEvents();
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $data = [
        'id_event' => $_POST['id_event'],
        'id_user' => $_POST['id_user'],
        'nom_user' => $_POST['nom_user'],
        'accom_res' => isset($_POST['accomp_res']) ? intval($_POST['accomp_res']) : 0
    ];

    if (ReservationController::handleAddReservation($data)) {
        $successMessage = 'Réservation effectuée avec succès!';
    } else {
        $successMessage = 'Erreur: La réservation n\'a pas pu être effectuée.';
    }
}

?>



<?php include 'C:\xampp\htdocs\GreenStartConnect\View\FrontOffice\headerFront.php'; ?>

<body>
<div class="container mt-4">
    <h1 class="mb-4">Liste des Événements</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-3">
            <input type="text" id="searchText" class="form-control" placeholder="Recherche par titre ou lieu">
        </div>
        <div class="col-md-3">
            <input type="date" id="dateStart" class="form-control" placeholder="Date début">
        </div>
        <div class="col-md-3">
            <input type="date" id="dateEnd" class="form-control" placeholder="Date fin">
        </div>
        <div class="col-md-3">
            <input type="number" id="maxParticipants" class="form-control" placeholder="Max participants ≤">
        </div>
        <div class="col-md-3">
    <button class="btn btn-warning w-100" onclick="resetFilters()">Réinitialiser les filtres</button>
</div>

    </div>

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
            <div class="mb-3">
                <label for="id_user" class="form-label">ID de l'utilisateur</label>
                <input type="number" class="form-control" name="id_user" id="id_user" required>
            </div>
            <div class="mb-3">
                <label for="nom_user" class="form-label">Votre nom</label>
                <input type="text" class="form-control" name="nom_user" id="nom_user" required>
            </div>
            <div class="mb-3">
                <label for="accomp_res" class="form-label">Nombre d'accompagnants</label>
                <input type="number" class="form-control" name="accomp_res" id="accomp_res" min="0" value="0" required>
            </div>
            <input type="hidden" name="action" value="add">
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

    // Filter logic
    const filters = {
        search: '',
        start: '',
        end: '',
        max: ''
    };

    document.getElementById('searchText').addEventListener('input', (e) => {
        filters.search = e.target.value.toLowerCase();
        filterRows();
    });

    document.getElementById('dateStart').addEventListener('change', (e) => {
        filters.start = e.target.value;
        filterRows();
    });

    document.getElementById('dateEnd').addEventListener('change', (e) => {
        filters.end = e.target.value;
        filterRows();
    });

    document.getElementById('maxParticipants').addEventListener('input', (e) => {
        filters.max = e.target.value;
        filterRows();
    });

    function filterRows() {
        const rows = document.querySelectorAll('#eventTable tr');
        rows.forEach(row => {
            const title = row.cells[0].innerText.toLowerCase();
            const lieu = row.cells[4].innerText.toLowerCase();
            const dateDebut = row.cells[2].innerText;
            const maxPart = parseInt(row.cells[5].innerText);

            let match = true;

            if (filters.search && !title.includes(filters.search) && !lieu.includes(filters.search)) match = false;
            if (filters.start && dateDebut < filters.start) match = false;
            if (filters.end && dateDebut > filters.end) match = false;
            if (filters.max && maxPart > parseInt(filters.max)) match = false;

            row.style.display = match ? '' : 'none';
        });
    }

    // Sorting
    let currentSort = { col: null, dir: 'asc' };

    function sortTable(colIndex) {
        const tbody = document.getElementById("eventTable");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const isNumeric = !isNaN(rows[0].cells[colIndex].innerText.trim());

        const direction = currentSort.col === colIndex && currentSort.dir === 'asc' ? 'desc' : 'asc';
        currentSort = { col: colIndex, dir: direction };

        rows.sort((a, b) => {
            let aText = a.cells[colIndex].innerText.trim();
            let bText = b.cells[colIndex].innerText.trim();

            if (isNumeric) {
                return direction === 'asc' ? aText - bText : bText - aText;
            } else {
                return direction === 'asc'
                    ? aText.localeCompare(bText)
                    : bText.localeCompare(aText);
            }
        });

        // Replace rows
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));

        // Update sort icons
        for (let i = 0; i <= 5; i++) {
            document.getElementById('sortIcon' + i).innerText = '▲▼';
        }
        document.getElementById('sortIcon' + colIndex).innerText = direction === 'asc' ? '▲' : '▼';
    }
</script>
</body>

<?php include 'C:\xampp\htdocs\GreenStartConnect\View\FrontOffice\footerFront.php'; ?>
