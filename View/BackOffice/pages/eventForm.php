<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';


// Generate CSRF token (same logic as in EventController)
$csrfSecret = 'your_secure_csrf_secret_key'; // Replace with a strong, unique key
$csrfToken = hash_hmac('sha256', 'event_form', $csrfSecret);

// Mode ajout ou modification
$mode = 'add';
$event = [
    'titre_event' => '',
    'description_event' => '',
    'localisation' => '',
    'date_debut' => '',
    'date_fin' => '',
    'max_participants' => ''
];

if (isset($_GET['id'])) {
    $mode = 'edit';
    $event = EventController::getEventById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = [
        'titre_event' => $_POST['titre_event'],
        'description_event' => $_POST['description_event'],
        'localisation' => $_POST['localisation'],
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'max_participants' => $_POST['max_participants']
    ];

    try {
        if (isset($_GET['id'])) {
            // Mode édition
            EventController::updateEvent($_GET['id'], $event);
        } else {
            // Mode ajout
            EventController::handleAddEvent($event);
        }
        // Redirection après sauvegarde
        header("Location: eventList.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include  'C:\xampp\htdocs\GreenStartConnect/View/BackOffice/headerBack.php';
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<!-- Formulaire -->
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5><?= ($mode === 'add') ? 'Ajouter un événement' : 'Modifier un événement' ?></h5>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <form method="POST" action="" class="needs-validation" novalidate>
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
          <div class="row">
            <div class="col-md-6">
              <!-- Titre -->
              <div class="form-group mb-3">
                <label for="titre_event">Titre de l'événement</label>
                <input type="text" class="form-control" name="titre_event" id="titre_event" value="<?= htmlspecialchars($event['titre_event']) ?>" required>
              </div>

              <!-- Description -->
              <div class="form-group mb-3">
                <label for="description_event">Description</label>
                <textarea class="form-control" name="description_event" id="description_event" rows="3" required><?= htmlspecialchars($event['description_event']) ?></textarea>
              </div>

              <!-- Localisation -->
              <div class="form-group mb-3">
                <label for="localisation">📍 Localisation</label>
                <input type="text" class="form-control" id="localisation" name="localisation" placeholder="Cliquez sur le bouton" readonly required>
                <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#mapModal">Choisir sur la carte</button>
              </div>
            </div>

            <div class="col-md-6">
              <!-- Date de début -->
              <div class="form-group mb-3">
                <label for="date_debut">Date de début</label>
                <input type="datetime-local" class="form-control" name="date_debut" id="date_debut" value="<?= htmlspecialchars($event['date_debut']) ?>" required>
              </div>

              <!-- Date de fin -->
              <div class="form-group mb-3">
                <label for="date_fin">Date de fin</label>
                <input type="datetime-local" class="form-control" name="date_fin" id="date_fin" value="<?= htmlspecialchars($event['date_fin']) ?>" required>
              </div>

              <!-- Max participants -->
              <div class="form-group mb-3">
                <label for="max_participants">Max participants</label>
                <input type="number" class="form-control" name="max_participants" id="max_participants" value="<?= htmlspecialchars($event['max_participants']) ?>" min="1" required>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary"><?= ($mode === 'add') ? 'Ajouter' : 'Modifier' ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de carte -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Choisir un lieu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="map" style="height: 400px; width: 100%;"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button class="btn btn-primary" id="confirm-location" data-bs-dismiss="modal">Confirmer</button>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<!-- Map Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    let map, marker;
    const modal = document.getElementById('mapModal');

    // Initialize map when modal is shown
    modal.addEventListener('shown.bs.modal', () => {
        if (!map) {
            // Initialize Leaflet map
            map = L.map('map').setView([36.8065, 10.1815], 13);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Handle map click to place marker and get address
            map.on('click', async (e) => {
                const { lat, lng } = e.latlng;
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
                        headers: { 'User-Agent': 'GreenStartConnect/1.0' }
                    });
                    const data = await res.json();
                    const addr = data.address;
                    const city = addr.city || addr.town || addr.village || '';
                    const road = addr.road || '';
                    const region = addr.state || '';
                    document.getElementById('localisation').value = `${road}, ${city}, ${region}`;
                } catch (err) {
                    document.getElementById('localisation').value = 'Erreur de géolocalisation';
                    console.error('Geocoding error:', err);
                }
            });
        }

        // Ensure map renders correctly by invalidating size after modal is fully shown
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });

    // Handle confirm button click
    document.getElementById('confirm-location').addEventListener('click', () => {
        const location = document.getElementById('localisation').value;
        if (location && location !== 'Erreur de géolocalisation') {
            alert(`Localisation confirmée: ${location}`);
        } else {
            alert('Veuillez choisir une localisation sur la carte.');
        }
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<?php
include  'C:\xampp\htdocs\GreenStartConnect/View/BackOffice/footerBack.php';
?>