<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';

// Fetch all events using the controller
$events = EventController::getAllEvents();

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
  $id = intval($_GET['id']);
  EventController::handleDeleteEvent($id);
  header("Location: eventList.php"); // Rediriger pour éviter la resoumission
  exit;
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

// Mode modification : récupération des données existantes
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $mode = 'edit';
    $event = EventController::getEventById($_GET['id']);
    if (!$event) {
        die("Événement introuvable.");
    }
}

// Traitement du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre_event' => $_POST['titre_event'],
        'description_event' => $_POST['description_event'],
        'localisation' => $_POST['localisation'],
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'max_participants' => $_POST['max_participants']
    ];

    if ($mode === 'edit') {
        EventController::handleUpdateEvent($_GET['id'], $data);
    } else {
        EventController::handleAddEvent($data);
    }

    header('Location: eventList.php');
    exit();
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
      <h5>Liste des évènements</h5>
    </div>
    <div class="card-body">
      <?php if (!empty($events)): ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Lieu</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Participants max.</th>
            <th>Actions</th> <!-- Nouvelle colonne pour les actions -->
          </tr>
        </thead>
        <tbody>
          <?php foreach ($events as $event): ?>
          <tr>
            <td><?= htmlspecialchars($event['id_event']) ?></td>
            <td><?= htmlspecialchars($event['titre_event']) ?></td>
            <td><?= htmlspecialchars($event['description_event']) ?></td>
            <td><?= htmlspecialchars($event['localisation']) ?></td>
            <td><?= htmlspecialchars($event['date_debut']) ?></td>
            <td><?= htmlspecialchars($event['date_fin']) ?></td>
            <td><?= htmlspecialchars($event['max_participants']) ?></td>
            <td>
  <a href="eventForm.php?id=<?= $event['id_event'] ?>" class="btn btn-warning btn-sm">Modifier</a>
  <a href="?action=delete&id=<?= $event['id_event'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer</a>
</td>

          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
      <p>Aucun événement à afficher.</p>
      <?php endif; ?>
    </div>
  </div>
</div>







            <div class="card-body">
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