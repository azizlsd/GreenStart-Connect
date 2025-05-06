<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';

$controller = new EventController();

// Total number of events
$totalEvents = count(EventController::getAllEvents());

// Unique participants (you need this method in EventController)
$uniqueParticipants = $controller->getUniqueParticipantsCount(); // Define this in eventC.php

// Most popular event (based on reservations)
$mostPopularEvent = $controller->getMostPopularEvent(); // You can define this too
?>

<div class="row">
  <div class="col-md-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-header">Total des événements</div>
      <div class="card-body">
        <h5 class="card-title"><?= $totalEvents ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-success mb-3">
      <div class="card-header">Participants uniques</div>
      <div class="card-body">
        <h5 class="card-title"><?= $uniqueParticipants ?></h5>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-warning mb-3">
      <div class="card-header">Événement le plus populaire</div>
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($mostPopularEvent['titre_event']) ?></h5>
        <p class="card-text"><?= $mostPopularEvent['total_reservations'] ?> réservations</p>
      </div>
    </div>
  </div>
</div>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?>
