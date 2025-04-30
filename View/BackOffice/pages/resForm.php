<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';

$mode = 'add';
$reservation = [
    'id_event' => '',
    'id_user' => '',
    'nom_user' => ''
];

// Mode modification : récupération des données existantes
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $mode = 'edit';
    $reservation = ReservationController::getReservationById($_GET['id']);
    if (!$reservation) {
        die("Réservation introuvable.");
    }
}

// Traitement du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_event' => $_POST['id_event'],
        'id_user' => $_POST['id_user'],
        'nom_user' => $_POST['nom_user']
    ];

    if ($mode === 'edit') {
        ReservationController::handleUpdateReservation($_GET['id'], $data);
    } else {
        ReservationController::handleAddReservation($data);
    }

    header('Location: reservationList.php');
    exit();
}
?>

<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\headerBack.php';  // Include the header file at the top
?>

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= ($mode === 'add') ? 'Ajouter une réservation' : 'Modifier une réservation' ?></h5>
            </div>
            <div class="card-body">

                <!-- HTML FORMULAIRE -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><?= ($mode === 'add') ? 'Ajouter une réservation' : 'Modifier une réservation' ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="id_event">ID Événement</label>
                                            <input type="text" class="form-control" name="id_event" id="id_event" value="<?= htmlspecialchars($reservation['id_event']) ?>" required>
                                            <div class="invalid-feedback">Veuillez entrer un ID d'événement.</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="id_user">ID Utilisateur</label>
                                            <input type="text" class="form-control" name="id_user" id="id_user" value="<?= htmlspecialchars($reservation['id_user']) ?>" required>
                                            <div class="invalid-feedback">Veuillez entrer un ID utilisateur.</div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="nom_user">Nom de l'utilisateur</label>
                                            <input type="text" class="form-control" name="nom_user" id="nom_user" value="<?= htmlspecialchars($reservation['nom_user']) ?>" required>
                                            <div class="invalid-feedback">Veuillez entrer le nom de l'utilisateur.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary"><?= ($mode === 'add') ? 'Ajouter la réservation' : 'Enregistrer les modifications' ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<?php
    include 'C:\xampp\htdocs\GreenStartConnect\View\BackOffice\footerBack.php';  // Include the footer
?>
