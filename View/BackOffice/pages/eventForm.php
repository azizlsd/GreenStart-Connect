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

// Fonction de validation
function validateEventData($data) {
    $errors = [];
    
    // Validation du titre (non vide et longueur minimum)
    if (empty($data['titre_event'])) {
        $errors['titre_event'] = "Le titre est obligatoire";
    } elseif (strlen($data['titre_event']) < 3) {
        $errors['titre_event'] = "Le titre doit contenir au moins 3 caractères";
    }
    
    // Validation de la description (non vide et longueur minimum)
    if (empty($data['description_event'])) {
        $errors['description_event'] = "La description est obligatoire";
    } elseif (strlen($data['description_event']) < 10) {
        $errors['description_event'] = "La description doit contenir au moins 10 caractères";
    }
    
    // Validation de la localisation
    if (empty($data['localisation'])) {
        $errors['localisation'] = "La localisation est obligatoire";
    }
    
    // Validation des dates
    $date_debut = strtotime($data['date_debut']);
    $date_fin = strtotime($data['date_fin']);
    $now = time();
    
    if (empty($data['date_debut'])) {
        $errors['date_debut'] = "La date de début est obligatoire";
    } elseif ($date_debut < $now) {
        $errors['date_debut'] = "La date de début ne peut pas être dans le passé";
    }
    
    if (empty($data['date_fin'])) {
        $errors['date_fin'] = "La date de fin est obligatoire";
    } elseif ($date_fin <= $date_debut) {
        $errors['date_fin'] = "La date de fin doit être postérieure à la date de début";
    }
    
    // Validation du nombre maximum de participants
    if (empty($data['max_participants'])) {
        $errors['max_participants'] = "Le nombre maximum de participants est obligatoire";
    } elseif (!is_numeric($data['max_participants']) || $data['max_participants'] < 1) {
        $errors['max_participants'] = "Le nombre de participants doit être un nombre positif";
    } elseif ($data['max_participants'] > 1000) { // Exemple de limite maximale
        $errors['max_participants'] = "Le nombre de participants ne peut pas dépasser 1000";
    }
    
    return $errors;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre_event' => trim($_POST['titre_event']),
        'description_event' => trim($_POST['description_event']),
        'localisation' => trim($_POST['localisation']),
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'max_participants' => $_POST['max_participants']
    ];

    // Valider les données
    $errors = validateEventData($data);

    // Si pas d'erreurs, procéder à l'enregistrement
    if (empty($errors)) {
        if ($mode === 'add') {
            EventController::handleAddEvent($data);
        } else {
            EventController::handleUpdateEvent($_GET['id'], $data);
        }
        header('Location: eventList.php');
        exit();
    }
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
                            <input type="text" class="form-control <?= isset($errors['titre_event']) ? 'is-invalid' : '' ?>" 
                                   name="titre_event" id="titre_event" 
                                   value="<?= htmlspecialchars($data['titre_event'] ?? $event['titre_event']) ?>" required>
                            <?php if (isset($errors['titre_event'])): ?>
                                <div class="invalid-feedback"><?= $errors['titre_event'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description_event">Description</label>
                            <textarea class="form-control <?= isset($errors['description_event']) ? 'is-invalid' : '' ?>" name="description_event" id="description_event" rows="3" required><?= htmlspecialchars($data['description_event'] ?? $event['description_event']) ?></textarea>
                            <?php if (isset($errors['description_event'])): ?>
                                <div class="invalid-feedback"><?= $errors['description_event'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="localisation">Lieu</label>
                            <input type="text" class="form-control <?= isset($errors['localisation']) ? 'is-invalid' : '' ?>" name="localisation" id="localisation" value="<?= htmlspecialchars($data['localisation'] ?? $event['localisation']) ?>" required>
                            <?php if (isset($errors['localisation'])): ?>
                                <div class="invalid-feedback"><?= $errors['localisation'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="date_debut">Date de début</label>
                            <input type="datetime-local" class="form-control <?= isset($errors['date_debut']) ? 'is-invalid' : '' ?>" name="date_debut" id="date_debut" value="<?= htmlspecialchars($data['date_debut'] ?? $event['date_debut']) ?>" required>
                            <?php if (isset($errors['date_debut'])): ?>
                                <div class="invalid-feedback"><?= $errors['date_debut'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date_fin">Date de fin</label>
                            <input type="datetime-local" class="form-control <?= isset($errors['date_fin']) ? 'is-invalid' : '' ?>" name="date_fin" id="date_fin" value="<?= htmlspecialchars($data['date_fin'] ?? $event['date_fin']) ?>" required>
                            <?php if (isset($errors['date_fin'])): ?>
                                <div class="invalid-feedback"><?= $errors['date_fin'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_participants">Nombre max. de participants</label>
                            <input type="number" class="form-control <?= isset($errors['max_participants']) ? 'is-invalid' : '' ?>" name="max_participants" id="max_participants" value="<?= htmlspecialchars($data['max_participants'] ?? $event['max_participants']) ?>" min="1" required>
                            <?php if (isset($errors['max_participants'])): ?>
                                <div class="invalid-feedback"><?= $errors['max_participants'] ?></div>
                            <?php endif; ?>
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