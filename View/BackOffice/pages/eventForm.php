<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';

// Déterminer le mode : ajout ou modification
$mode = 'add';
$event = [
    'titre_event' => '',
    'description_event' => '',
    'localisation' => '',
    'date_debut' => '',
    'date_fin' => '',
    'max_participants' => ''
];

// Si un ID est passé, on modifie un événement existant
if (isset($_GET['id'])) {
    $mode = 'edit';
    $event = EventController::getEventById($_GET['id']);
}

?>

<?php include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php'; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= ($mode === 'add') ? 'Ajouter un événement' : 'Modifier un événement' ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="eventHandler.php" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="titre_event">Titre de l'événement</label>
                                <input type="text" class="form-control <?= isset($errors['titre_event']) ? 'is-invalid' : '' ?>"
                                    name="titre_event" id="titre_event"
                                    value="<?= htmlspecialchars($event['titre_event']) ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description_event">Description</label>
                                <textarea class="form-control <?= isset($errors['description_event']) ? 'is-invalid' : '' ?>"
                                    name="description_event" id="description_event" rows="3" required><?= htmlspecialchars($event['description_event']) ?></textarea>
                            </div>

                            <!-- Localisation -->
                            <div class="form-group mb-3">
                                <label for="localisation">📍 Localisation (choisir sur la carte)</label>
                                <input type="text" class="form-control" id="localisation" name="localisation" placeholder="Cliquez sur le bouton pour choisir la localisation" value="<?= htmlspecialchars($event['localisation']) ?>" readonly required>
                                <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#mapModal">Choisir sur la carte</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_debut">Date de début</label>
                                <input type="datetime-local" class="form-control" name="date_debut" id="date_debut" value="<?= htmlspecialchars($event['date_debut']) ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="date_fin">Date de fin</label>
                                <input type="datetime-local" class="form-control" name="date_fin" id="date_fin" value="<?= htmlspecialchars($event['date_fin']) ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="max_participants">Nombre max. de participants</label>
                                <input type="number" class="form-control" name="max_participants" id="max_participants" value="<?= htmlspecialchars($event['max_participants']) ?>" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><?= ($mode === 'add') ? 'Ajouter l\'événement' : 'Enregistrer les modifications' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour la carte -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Choisir un lieu sur la carte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="confirm-location" data-bs-dismiss="modal">Confirmer la localisation</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([36.8065, 10.1815], 13); // Centre sur Tunis

    // Fond de carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', async function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);

        // Reverse geocoding via Nominatim
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
        const data = await response.json();

        if (data && data.address) {
            const city = data.address.city || data.address.town || data.address.village || data.address.hamlet || '';
            const road = data.address.road || '';
            const region = data.address.state || '';
            const displayName = data.display_name;

            document.getElementById('localisation').value = `${road}, ${city}, ${region}`;
        } else {
            document.getElementById('localisation').value = "Localisation inconnue";
        }
    });

    document.getElementById('confirm-location').addEventListener('click', function () {
        const location = document.getElementById('localisation').value;
        if (!location || location === "Localisation inconnue") {
            alert("Veuillez sélectionner une localisation valide sur la carte.");
            return;
        }

        alert(`Localisation confirmée: ${location}`);
    });
});
</script>

<?php include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php'; ?>
