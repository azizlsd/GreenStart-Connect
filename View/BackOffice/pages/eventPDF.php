<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\Controller\eventC.php';
require_once 'C:\xampp\htdocs\GreenStartConnect\fpdf\fpdf.php';

class PDF extends FPDF
{
    function Header()
    {
        $logoPath = 'C:\xampp\htdocs\GreenStartConnect\images\logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 6, 25);
        }

        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'GreenStartConnect - Liste des Événements', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 8, 'Généré le : ' . date('d/m/Y H:i'), 0, 1, 'R');
        $this->Ln(2);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }

    // Calcule la hauteur d'une MultiCell
    function getMultiCellHeight($w, $h, $txt)
    {
        $nb = substr_count($txt, "\n") + 1;
        $lines = $this->NbLines($w, $txt);
        return $h * $lines;
    }

    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function EventTable($header, $data)
    {
        $widths = [12, 35, 50, 35, 25, 25, 25]; // ID, Title, Description, Location, Start, End, Max
        $aligns = ['C', 'L', 'L', 'L', 'C', 'C', 'C'];

        // Header
        $this->SetFillColor(0, 123, 255);
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 10);
        foreach ($header as $i => $col) {
            $this->Cell($widths[$i], 8, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(0);

        foreach ($data as $row) {
            $cellData = [
                $row['id_event'],
                utf8_decode($row['titre_event']),
                utf8_decode($row['description_event']),
                utf8_decode($row['localisation']),
                $row['date_debut'],
                $row['date_fin'],
                $row['max_participants']
            ];

            // Calcule la hauteur max pour cette ligne
            $lineHeights = [];
            foreach ($cellData as $i => $text) {
                $lineHeights[] = $this->NbLines($widths[$i], $text);
            }
            $maxLines = max($lineHeights);
            $rowHeight = 5 * $maxLines;

            $x = $this->GetX();
            $y = $this->GetY();

            for ($i = 0; $i < count($cellData); $i++) {
                $this->SetXY($x, $y);
                $this->MultiCell($widths[$i], 5, $cellData[$i], 1, $aligns[$i]);
                $x += $widths[$i];
            }

            $this->Ln();
        }
    }
}

// Get parameters
$searchTerm = $_GET['search'] ?? '';
$searchColumn = $_GET['searchColumn'] ?? 'titre_event';
$sortColumn = $_GET['sort'] ?? 'id_event';
$sortOrder = $_GET['order'] === 'desc' ? 'desc' : 'asc';

$allowed = ['titre_event', 'description_event', 'localisation', 'id_event', 'date_debut', 'date_fin'];
if (!in_array($searchColumn, $allowed)) $searchColumn = 'titre_event';
if (!in_array($sortColumn, $allowed)) $sortColumn = 'id_event';

$events = EventController::getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder);

// Generate PDF
ob_start();
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$headers = ['ID', 'Titre', 'Description', 'Lieu', 'Début', 'Fin', 'Max'];

// Filter info
if (!empty($searchTerm)) {
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 6, "Filtré par: $searchColumn contient \"$searchTerm\"", 0, 1);
}
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 6, "Trié par: $sortColumn (" . strtoupper($sortOrder) . ")", 0, 1);
$pdf->Ln(3);

$pdf->EventTable($headers, $events);

// Summary
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, 'Total des événements : ' . count($events), 0, 1);

ob_end_clean();
$pdf->Output('Liste_evenements.pdf', 'I');
exit;
?>
