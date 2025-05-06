<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php'; // database connection
require('C:\xampp\htdocs\GreenStartConnect\fpdf\fpdf.php'); // path to FPDF library

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Liste des Événements', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    function EventTable($header, $data)
    {
        $this->SetFont('Arial', 'B', 10);
        foreach ($header as $col) {
            $this->Cell(27, 7, $col, 1);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            $this->Cell(27, 6, $row['id_event'], 1);
            $this->Cell(27, 6, utf8_decode($row['titre_event']), 1);
            $this->Cell(27, 6, utf8_decode($row['description_event']), 1);
            $this->Cell(27, 6, utf8_decode($row['localisation']), 1);
            $this->Cell(27, 6, $row['date_debut'], 1);
            $this->Cell(27, 6, $row['date_fin'], 1);
            $this->Cell(27, 6, $row['max_participants'], 1);
            $this->Ln();
        }
    }
}

// Get events from database
$pdo = config::getConnexion();
$query = "SELECT * FROM events";
$stmt = $pdo->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Landscape orientation
$header = ['ID', 'Titre', 'Description', 'Lieu', 'Début', 'Fin', 'Max.'];
$pdf->EventTable($header, $events);
$pdf->Output('D', 'liste_evenements.pdf'); // Force download
?>
