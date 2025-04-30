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

if (isset($_GET['id'])) {
    $mode = 'edit';
    $event = EventController::getEventById($_GET['id']);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre_event' => $_POST['titre_event'],
        'description_event' => $_POST['description_event'],
        'localisation' => $_POST['localisation'],
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'max_participants' => $_POST['max_participants']
    ];

    if ($mode === 'add') {
        EventController::handleAddEvent($data);
    } else {
        EventController::handleUpdateEvent($_GET['id'], $data);
    }

    // Redirection après traitement
    header('Location: eventList.php');
    exit();
}
?>
<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';

$mode = 'add';
$event = [
    'titre_event' => '',
    'description_event' => '',
    'localisation' => '',
    'date_debut' => '',
    'date_fin' => '',
    'max_participants' => ''
];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $mode = 'edit';
    $eventFromDb = EventController::getEventById($_GET['id']);
    if ($eventFromDb) {
        $event = $eventFromDb;
    } else {
        die("Événement introuvable.");
    }
}
?>

<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';  // Include the header file at the top
?>


      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h5>Ajouter ou modifier un évènement</h5>
            </div>
            <div class="card-body">




            <!-- HTML FORMULAIRE -->
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5><?= ($mode === 'add') ? 'Ajouter un événement' : 'Modifier un événement' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="titre_event">Titre de l'événement</label>
                            <input type="text" class="form-control" name="titre_event" id="titre_event" value="<?= htmlspecialchars($event['titre_event']) ?>" required>
                            <div class="invalid-feedback">Veuillez entrer un titre.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description_event">Description</label>
                            <textarea class="form-control" name="description_event" id="description_event" rows="3" required><?= htmlspecialchars($event['description_event']) ?></textarea>
                            <div class="invalid-feedback">Veuillez entrer une description.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="localisation">Lieu</label>
                            <input type="text" class="form-control" name="localisation" id="localisation" value="<?= htmlspecialchars($event['localisation']) ?>" required>
                            <div class="invalid-feedback">Veuillez entrer un lieu.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="date_debut">Date de début</label>
                            <input type="datetime-local" class="form-control" name="date_debut" id="date_debut" value="<?= htmlspecialchars($event['date_debut']) ?>" required>
                            <div class="invalid-feedback">Veuillez entrer une date de début.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date_fin">Date de fin</label>
                            <input type="datetime-local" class="form-control" name="date_fin" id="date_fin" value="<?= htmlspecialchars($event['date_fin']) ?>" required>
                            <div class="invalid-feedback">Veuillez entrer une date de fin.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_participants">Nombre max. de participants</label>
                            <input type="number" class="form-control" name="max_participants" id="max_participants" value="<?= htmlspecialchars($event['max_participants']) ?>" min="1" required>
                            <div class="invalid-feedback">Veuillez entrer un nombre valide.</div>
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
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>
      <!-- [ Main Content ] end -->
    </div>
  </div>
  <!-- [ Main Content ] end -->
















  <?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';  // Include the header file at the top
?>