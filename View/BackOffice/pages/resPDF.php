<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php'; // Database connection
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\resC.php'; // ReservationController
require_once 'C:\xampp\htdocs\GreenStartConnect\fpdf\fpdf.php'; // FPDF library

// Start output buffering to prevent headers already sent errors
ob_start();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Liste des Réservations', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ReservationTable($header, $data)
    {
        // Adjusted cell widths for landscape A4 (297mm wide)
        $widths = [40, 40, 60, 40]; // Total: 180mm (leaving margins)
        $this->SetFont('Arial', 'B', 10);
        foreach ($header as $i => $col) {
            $this->Cell($widths[$i], 7, $col, 1);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            $this->Cell($widths[0], 6, $row['id_event'], 1);
            $this->Cell($widths[1], 6, $row['id_user'], 1);
            $this->Cell($widths[2], 6, utf8_decode($row['nom_user']), 1);
            $this->Cell($widths[3], 6, $row['accom_res'], 1);
            $this->Ln();
        }
    }
}

// Get search and sort parameters
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchColumn = isset($_GET['searchColumn']) ? $_GET['searchColumn'] : 'nom_user';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id_res';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

// Validate search/sort columns to prevent SQL injection
$allowedColumns = ['nom_user', 'id_user', 'id_event'];
if (!in_array($searchColumn, $allowedColumns)) {
    $searchColumn = 'nom_user';
}
$allowedSortColumns = ['id_event', 'id_user', 'nom_user', 'accom_res', 'id_res'];
if (!in_array($sortColumn, $allowedSortColumns)) {
    $sortColumn = 'id_res';
}

// Fetch reservations using ReservationController
$reservations = ReservationController::getFilteredReservations($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Check if reservations exist
if (empty($reservations)) {
    $pdf = new PDF();
    $pdf->AddPage('L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Aucune réservation à afficher.', 0, 1, 'C');
    $pdf->Output('D', 'liste_reservations.pdf');
    ob_end_clean();
    exit;
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Landscape orientation
$header = ['ID Événement', 'ID Utilisateur', 'Nom Utilisateur', 'Accompagnants'];
$pdf->ReservationTable($header, $reservations);

// Clean output buffer and output PDF
ob_end_clean();
$pdf->Output('D', 'liste_reservations.pdf');
exit;
?>