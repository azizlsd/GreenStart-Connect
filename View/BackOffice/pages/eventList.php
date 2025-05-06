<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';

// Récupérer la recherche, le critère de tri et le critère de recherche depuis les paramètres GET
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Mot de recherche
$searchColumn = isset($_GET['searchColumn']) ? $_GET['searchColumn'] : 'titre_event'; // Colonne à chercher
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id_event'; // Colonne à trier
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc'; // Ordre de tri (ascendant ou descendant)

// Rechercher et trier les événements
$events = EventController::getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Suppression d'un événement
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
  $id = intval($_GET['id']);
  EventController::handleDeleteEvent($id);
  header("Location: eventList.php"); // Rediriger pour éviter la resoumission
  exit;
}
?>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
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
        <!-- Formulaire de recherche -->
        <form method="GET" action="eventList.php" class="mb-3">
          <div class="row">
            <div class="col-md-4">
              <input type="text" name="search" class="form-control" placeholder="Rechercher un événement" value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
            <div class="col-md-4">
              <select name="searchColumn" class="form-control">
                <option value="titre_event" <?= $searchColumn == 'titre_event' ? 'selected' : '' ?>>Titre</option>
                <option value="description_event" <?= $searchColumn == 'description_event' ? 'selected' : '' ?>>Description</option>
                <option value="localisation" <?= $searchColumn == 'localisation' ? 'selected' : '' ?>>Lieu</option>
              </select>
            </div>
            <div class="col-md-4 text-end">
              <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
          </div>
        </form>
        <div class="text-end mb-3">
  <a href="eventPDF.php" class="btn btn-info">Télécharger en PDF</a>
  <a href="eventStats.php" class="btn btn-secondary">Voir les statistiques</a>
</div>




        <?php if (!empty($events)): ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>
                <a href="?sort=id_event&order=<?= $sortOrder === 'asc' ? 'desc' : 'asc' ?>">ID</a>
              </th>
              <th>
                <a href="?sort=titre_event&order=<?= $sortOrder === 'asc' ? 'desc' : 'asc' ?>">Titre</a>
              </th>
              <th>Description</th>
              <th>Lieu</th>
              <th>
                <a href="?sort=date_debut&order=<?= $sortOrder === 'asc' ? 'desc' : 'asc' ?>">Date de début</a>
              </th>
              <th>
                <a href="?sort=date_fin&order=<?= $sortOrder === 'asc' ? 'desc' : 'asc' ?>">Date de fin</a>
              </th>
              <th>Participants max.</th>
              <th>Actions</th>
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
                <a href="resForm.php?id_event=<?= $event['id_event'] ?>" class="btn btn-success btn-sm">Réserver</a>
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
</div>
<!-- [ Main Content ] end -->
<?php
include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?> 
