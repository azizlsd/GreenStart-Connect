<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';

// Get the event ID from the URL
if (isset($_GET['id_event']) && is_numeric($_GET['id_event'])) {
    $eventId = (int)$_GET['id_event'];

    // Verify event exists
    $eventController = new EventController();
    $event = $eventController->getEventById($eventId);

    if (!$event) {
        die("Événement introuvable.");
    }
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
$errors = [];

// Validation function for reservation data
function validateReservationData($data, $event, $reservationController, $isEdit = false) {
    $errors = [];

    // Validate nom_user
    if (empty($data['nom_user'])) {
        $errors['nom_user'] = "Le nom de l'utilisateur est requis.";
    } elseif (strlen($data['nom_user']) < 2) {
        $errors['nom_user'] = "Le nom doit contenir au moins 2 caractères.";
    } elseif (strlen($data['nom_user']) > 100) {
        $errors['nom_user'] = "Le nom ne peut pas dépasser 100 caractères.";
    } elseif (!preg_match('/^[a-zA-Z\s\'\-]+$/', $data['nom_user'])) {
        $errors['nom_user'] = "Le nom ne peut contenir que des lettres, espaces, apostrophes ou tirets.";
    }

    // Validate accom_res
    if (!isset($data['accom_res']) || $data['accom_res'] === '') {
        $errors['accom_res'] = "Le nombre d'accompagnants est requis.";
    } elseif (!is_numeric($data['accom_res']) || $data['accom_res'] < 0) {
        $errors['accom_res'] = "Le nombre d'accompagnants doit être un nombre positif.";
    } elseif ($data['accom_res'] > 100) {
        $errors['accom_res'] = "Le nombre d'accompagnants ne peut pas dépasser 100.";
    } else {
        // Calculate total reservations for the event (excluding current reservation in edit mode)
        $existingReservations = $reservationController->getReservationsByEventId($data['id_event']);
        $totalAccom = 0;
        foreach ($existingReservations as $res) {
            if ($isEdit && isset($data['id_res']) && $res['id_res'] == $data['id_res']) {
                continue; // Skip current reservation in edit mode
            }
            $totalAccom += (int)$res['accom_res'];
        }

        $maxParticipants = (int)$event['max_participants'];
        $requestedTotal = $totalAccom + (int)$data['accom_res'];

        if ($requestedTotal > $maxParticipants) {
            $errors['accom_res'] = "Le nombre total de participants ($requestedTotal) dépasse la capacité de l'événement ($maxParticipants).";
        }
    }

    return $errors;
}

// Check if reservation exists for editing
$reservationController = new ReservationController();
$isEdit = false;
if (isset($_GET['id_res']) && is_numeric($_GET['id_res'])) {
    $reservation = $reservationController->getReservationById($_GET['id_res']);
    if (!$reservation) {
        die("Réservation introuvable.");
    }
    $isEdit = true;
} else {
    $reservation['id_user'] = '';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_event' => $eventId,
        'id_user' => trim($_POST['id_user'] ?? ''),
        'nom_user' => trim($_POST['nom_user'] ?? ''),
        'accom_res' => trim($_POST['accom_res'] ?? '')
    ];

    if ($isEdit && isset($_POST['id_res']) && is_numeric($_POST['id_res'])) {
        $data['id_res'] = (int)$_POST['id_res'];
    }

    $errors = validateReservationData($data, $event, $reservationController, $isEdit);

    if (empty($errors)) {
        try {
            if (!$isEdit) {
                $reservationController->handleAddReservation($data);
            } else {
                $reservationController->handleModifyReservation($data);
            }
            header('Location: resList.php');
            exit();
        } catch (Exception $e) {
            $errors['general'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}

require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';
?>

<!-- Custom CSS for Error Styling -->
<style>
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    .alert {
        margin-top: 10px;
    }
</style>

<!-- HTML FORMULAIRE -->
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une réservation pour l'événement : <?= htmlspecialchars($event['titre_event']) ?></h5>
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="needs-validation" novalidate>
                <input type="hidden" name="id_event" value="<?= htmlspecialchars($eventId) ?>">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id_res" value="<?= htmlspecialchars($reservation['id_res']) ?>">
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nom utilisateur -->
                        <div class="form-group mb-3">
                            <label for="nom_user">Nom de l'utilisateur</label>
                            <input type="text"
                                   class="form-control <?= isset($errors['nom_user']) ? 'is-invalid' : '' ?>"
                                   name="nom_user" id="nom_user"
                                   value="<?= htmlspecialchars($reservation['nom_user']) ?>"
                                   required minlength="2" maxlength="100"
                                   pattern="[a-zA-Z\s'\-]+">
                            <div class="invalid-feedback">
                                <?= isset($errors['nom_user']) ? htmlspecialchars($errors['nom_user']) : 'Veuillez entrer un nom valide (2-100 caractères, lettres, espaces, apostrophes, tirets).' ?>
                            </div>
                        </div>

                        <!-- Nombre d'accompagnants -->
                        <div class="form-group mb-3">
                            <label for="accom_res">Nombre d'accompagnants</label>
                            <input type="number"
                                   class="form-control <?= isset($errors['accom_res']) ? 'is-invalid' : '' ?>"
                                   name="accom_res" id="accom_res"
                                   value="<?= htmlspecialchars($reservation['accom_res']) ?>"
                                   min="0" max="100" required
                                   data-max-participants="<?= htmlspecialchars($event['max_participants']) ?>">
                            <div class="invalid-feedback">
                                <?= isset($errors['accom_res']) ? htmlspecialchars($errors['accom_res']) : 'Veuillez entrer un nombre d\'accompagnants entre 0 et 100, sans dépasser la capacité de l\'événement.' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Modifier la réservation' : 'Ajouter la réservation' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Client-Side Validation Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.needs-validation');
    const accomResInput = document.getElementById('accom_res');
    const maxParticipants = parseInt(accomResInput.dataset.maxParticipants) || 100;

    form.addEventListener('submit', (e) => {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Validate accom_res against max_participants
        const accomRes = parseInt(accomResInput.value);
        if (isNaN(accomRes) || accomRes < 0) {
            accomResInput.classList.add('is-invalid');
            accomResInput.nextElementSibling.textContent = 'Le nombre d\'accompagnants doit être un nombre positif.';
            e.preventDefault();
        } else if (accomRes > maxParticipants) {
            accomResInput.classList.add('is-invalid');
            accomResInput.nextElementSibling.textContent = `Le nombre d'accompagnants ne peut pas dépasser la capacité de l'événement (${maxParticipants}).`;
            e.preventDefault();
        } else {
            accomResInput.classList.remove('is-invalid');
        }

        form.classList.add('was-validated');
    });

    // Real-time validation for accom_res
    accomResInput.addEventListener('input', () => {
        const accomRes = parseInt(accomResInput.value);
        if (isNaN(accomRes) || accomRes < 0) {
            accomResInput.classList.add('is-invalid');
            accomResInput.nextElementSibling.textContent = 'Le nombre d\'accompagnants doit être un nombre positif.';
        } else if (accomRes > maxParticipants) {
            accomResInput.classList.add('is-invalid');
            accomResInput.nextElementSibling.textContent = `Le nombre d'accompagnants ne peut pas dépasser la capacité de l'événement (${maxParticipants}).`;
        } else {
            accomResInput.classList.remove('is-invalid');
        }
    });
});
</script>

<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';
?>