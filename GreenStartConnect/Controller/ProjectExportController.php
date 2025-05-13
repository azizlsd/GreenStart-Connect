<?php
require_once __DIR__ . '/../Model/Project.php';
require_once __DIR__ . '/../config/config.php';
class ProjectExportController {
    private $pdo;

    public function __construct() {
        $dbC = new Config();
        $this->pdo = $dbC->getConnection();
    }

    public function exportPDF() {
        try {
            // Récupérer tous les projets
            $stmt = $this->pdo->query("SELECT * FROM projects ORDER BY title");
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Créer le contenu HTML
            $html = '<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Liste des Projets</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            h1 { text-align: center; color: #2c3e50; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f8f9fa; }
                            tr:nth-child(even) { background-color: #f9f9f9; }
                        </style>
                    </head>
                    <body>
                        <h1>Liste des Projets</h1>
                        <table>
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Statut</th>
                                    <th>Budget</th>
                                </tr>
                            </thead>
                            <tbody>';

            foreach ($projects as $project) {
                $html .= '<tr>
                            <td>' . htmlspecialchars($project['title']) . '</td>
                            <td>' . htmlspecialchars(substr($project['description'], 0, 100)) . '...</td>
                            <td>' . date('d/m/Y', strtotime($project['start_date'])) . '</td>
                            <td>' . date('d/m/Y', strtotime($project['end_date'])) . '</td>
                            <td>' . htmlspecialchars($project['status']) . '</td>
                            <td>' . number_format($project['budget'], 2, ',', ' ') . ' €</td>
                        </tr>';
            }

            $html .= '</tbody></table></body></html>';

            // Envoyer les en-têtes pour le téléchargement Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="liste_projets.xls"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Afficher le contenu
            echo $html;
            exit;

        } catch (Exception $e) {
            error_log("Erreur lors de l'export: " . $e->getMessage());
            header('Location: index.php?controller=project&error=export');
            exit;
        }
    }
} 