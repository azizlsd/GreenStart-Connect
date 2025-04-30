<?php
// Include necessary files for event and reservation handling
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

// Fetch events from the EventController
$events = EventController::getAllEvents();

// Initialize success message
$successMessage = '';

// Handle the reservation form submission if data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Get the data from the form submission
    $data = [
        'id_event' => $_POST['id_event'],
        'id_user' => $_POST['id_user'],
        'nom_user' => $_POST['nom_user']
    ];

    // Add the reservation using the ReservationController
    if (ReservationController::handleAddReservation($data)) {
        // Set the success message if the reservation is successful
        $successMessage = 'Réservation effectuée avec succès!';
    } else {
        // Handle any errors, like if the reservation fails
        $successMessage = 'Erreur: La réservation n\'a pas pu être effectuée.';
    }
}
?>


<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\FrontOffice\headerFront.php';  // Include the header file at the top
?>











<body>
    <h1>Liste des Événements</h1>

    <!-- Display the success message -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Lieu</th>
                <th>Max participants</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['titre_event']) ?></td>
                    <td><?= htmlspecialchars(substr($event['description_event'], 0, 50)) ?>...</td>
                    <td><?= date('d/m/Y H:i', strtotime($event['date_debut'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($event['date_fin'])) ?></td>
                    <td><?= htmlspecialchars($event['localisation']) ?></td>
                    <td><?= htmlspecialchars($event['max_participants']) ?></td>
                    <td>
                        <button class="btn btn-primary" onclick="openPopup(<?= $event['id_event'] ?>)">Réserver</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Aucun événement trouvé.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="popupForm" style="display: none; position: fixed; top: 25%; left: 35%; background: white; padding: 20px; border: 1px solid #ccc; z-index: 999;">
        <h3>Réserver un événement</h3>
        <form method="POST" action="event.php">
            <input type="hidden" name="id_event" id="popupEventId">
            <div class="form-group">
                <label for="id_user">ID de l'utilisateur</label>
                <input type="number" class="form-control" name="id_user" id="id_user" required>
            </div>
            <div class="form-group">
                <label for="nom_user">Votre nom</label>
                <input type="text" class="form-control" name="nom_user" id="nom_user" required>
            </div>
            <input type="hidden" name="action" value="add"> <!-- Action to handle the reservation -->

            <br>
            <button type="submit" class="btn btn-success">Valider</button>
            <button type="button" class="btn btn-secondary" onclick="closePopup()">Annuler</button>
        </form>
    </div>

    <script>
        function openPopup(eventId) {
            document.getElementById('popupEventId').value = eventId;
            document.getElementById('popupForm').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popupForm').style.display = 'none';
        }
    </script>
</body>








<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\FrontOffice\footerFront.php';  // Include the header file at the top
?>

    