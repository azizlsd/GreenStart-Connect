<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\fpdf\fpdf.php';

ob_start();

class PDF extends FPDF
{
    function Header()
    {
        // Logo (optionnel)
        if (file_exists('C:\xampp\htdocs\GreenStartConnect\images\logo.png')) {
            $this->Image('C:\xampp\htdocs\GreenStartConnect\images\logo.png', 10, 6, 30);
        }

        // Titre
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('GreenStartConnect - Liste des Réservations'), 0, 1, 'C');

        // Date
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Exporté le : ' . date('d/m/Y H:i'), 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 10, utf8_decode('GreenStartConnect - Système de Réservations'), 0, 0, 'C');
    }

    function ReservationTable($header, $data)
    {
        $widths = [50, 50, 100, 40]; // Colonnes ajustées
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(52, 152, 219); // Bleu clair
        $this->SetTextColor(255); // Blanc
        $this->SetDrawColor(41, 128, 185);

        foreach ($header as $i => $col) {
            $this->Cell($widths[$i], 8, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0);
        $fill = false;

        foreach ($data as $row) {
            $this->SetFillColor(245, 245, 245); // Gris clair pour alternance
            $this->Cell($widths[0], 8, $row['id_event'], 1, 0, 'C', $fill);
            $this->Cell($widths[1], 8, $row['id_user'], 1, 0, 'C', $fill);
            $this->Cell($widths[2], 8, utf8_decode($row['nom_user']), 1, 0, 'L', $fill);
            $this->Cell($widths[3], 8, $row['accom_res'], 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

// Sécurisation des paramètres
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchColumn = in_array($_GET['searchColumn'] ?? '', ['nom_user', 'id_user', 'id_event']) ? $_GET['searchColumn'] : 'nom_user';
$sortColumn = in_array($_GET['sort'] ?? '', ['id_event', 'id_user', 'nom_user', 'accom_res', 'id_res']) ? $_GET['sort'] : 'id_res';
$sortOrder = ($_GET['order'] ?? '') === 'desc' ? 'desc' : 'asc';

// Récupération des données
$reservations = ReservationController::getFilteredReservations($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Création PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Landscape
$header = ['ID Événement', 'ID Utilisateur', 'Nom Utilisateur', 'Accompagnants'];

if (empty($reservations)) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Aucune réservation trouvée.'), 0, 1, 'C');
} else {
    $pdf->ReservationTable($header, $reservations);
}

ob_end_clean();
$pdf->Output('D', 'liste_reservations.pdf');
exit;
?>
