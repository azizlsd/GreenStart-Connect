<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

// Get the event ID from the URL
if (isset($_GET['id_event']) && is_numeric($_GET['id_event'])) {
    $eventId = $_GET['id_event'];
} else {
    die("Événement introuvable.");
}

// Initialize reservation data
$reservation = [
    'id_event' => $eventId,
    'id_user' => '',
    'nom_user' => '',
    'accom_res' => ''
];

// Validation function for reservation data
function validateReservationData($data) {
    $errors = [];
    if (empty($data['nom_user'])) {
        $errors['nom_user'] = "Le nom de l'utilisateur est obligatoire";
    }

    if (empty($data['accom_res'])) {
        $errors['accom_res'] = "Le nombre d'accompagnants est obligatoire";
    } elseif (!is_numeric($data['accom_res']) || $data['accom_res'] < 0) {
        $errors['accom_res'] = "Le nombre d'accompagnants doit être un nombre positif";
    }

    return $errors;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_event' => $eventId,
        'id_user' => $_POST['id_user'],
        'nom_user' => $_POST['nom_user'],
        'accom_res' => $_POST['accom_res']
    ];

    $errors = validateReservationData($data);

    // If no errors, proceed to add the reservation
    if (empty($errors)) {
        ReservationController::handleAddReservation($data);
        header('Location: resList.php');
        exit();
    }
}
?>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
?>

<!-- HTML FORMULAIRE -->
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>Ajouter une réservation pour l'événement</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nom utilisateur -->
                        <div class="form-group mb-3">
                            <label for="nom_user">Nom de l'utilisateur</label>
                            <input type="text"
                                   class="form-control <?= isset($errors['nom_user']) ? 'is-invalid' : '' ?>"
                                   name="nom_user" id="nom_user"
                                   value="<?= htmlspecialchars($reservation['nom_user']) ?>"
                                   required>
                            <?php if (isset($errors['nom_user'])): ?>
                                <div class="invalid-feedback"><?= $errors['nom_user'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Nombre d'accompagnants -->
                        <div class="form-group mb-3">
                            <label for="accom_res">Nombre d'accompagnants</label>
                            <input type="number"
                                   class="form-control <?= isset($errors['accom_res']) ? 'is-invalid' : '' ?>"
                                   name="accom_res" id="accom_res"
                                   value="<?= htmlspecialchars($reservation['accom_res']) ?>"
                                   min="0" required>
                            <?php if (isset($errors['accom_res'])): ?>
                                <div class="invalid-feedback"><?= $errors['accom_res'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Ajouter la réservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?>
