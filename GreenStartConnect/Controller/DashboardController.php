<?php
require_once __DIR__ . '/../Model/Project.php';
require_once __DIR__ . '/../Model/Event.php';
require_once __DIR__ . '/../config/config.php';
class DashboardController {
    private $pdo;

    public function __construct() {
         $dbC = new Config();
        $this->pdo = $dbC->getConnection();
    }

    public function index() {
        // Récupérer les statistiques des projets
        $totalProjects = $this->getTotalProjects();
        $totalBudget = $this->getTotalBudget();
        $activeProjects = $this->getActiveProjects();
        $recentProjects = $this->getRecentProjects();
        $projectsByStatus = $this->getProjectsByStatus();
        $projectsOverTime = $this->getProjectsOverTime();

        // Récupérer les statistiques des événements
        $totalEvents = $this->getTotalEvents();

        // Statistiques postulations
        $totalPostulations = $this->getTotalPostulations();
        $postulationsByStatus = $this->getPostulationsByStatus();
        $postulationsOverTime = $this->getPostulationsOverTime();
        $postulationStatusByProject = $this->getPostulationStatusByProject();

        // Inclure la vue du dashboard
        $recentPostulations = $this->getRecentPostulations();
        include 'View/admin/dashboard.php';
    }

    private function getTotalProjects() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM projects");
        return $stmt->fetchColumn();
    }

    private function getTotalBudget() {
        $stmt = $this->pdo->query("SELECT SUM(budget) FROM projects");
        return $stmt->fetchColumn() ?: 0;
    }

    private function getActiveProjects() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'En cours'");
        return $stmt->fetchColumn();
    }

    private function getRecentProjects($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getProjectsByStatus() {
        $stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM projects GROUP BY status");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['status']] = (int)$row['count'];
        }
        return $result;
    }

    private function getProjectsOverTime() {
        // Récupérer le nombre de projets par mois sur les 6 derniers mois
        $stmt = $this->pdo->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM projects
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = new DateTime($row['month'] . '-01');
            $result[$date->format('M Y')] = (int)$row['count'];
        }
        return $result;
    }

    private function getTotalEvents() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM events");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Si la table n'existe pas ou autre erreur
            return 0;
        }
    }

    private function getTotalPostulations() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM postulations");
        return $stmt->fetchColumn();
    }

    private function getPostulationsByStatus() {
        $stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM postulations GROUP BY status");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['status']] = (int)$row['count'];
        }
        return $result;
    }

    private function getPostulationsOverTime() {
        $stmt = $this->pdo->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM postulations
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = new DateTime($row['month'] . '-01');
            $result[$date->format('M Y')] = (int)$row['count'];
        }
        return $result;
    }

    private function getRecentPostulations($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT p.*, pr.title as project_title FROM postulations p JOIN projects pr ON p.project_id = pr.id ORDER BY p.created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPostulationStatusByProject() {
        $stmt = $this->pdo->query("
            SELECT pr.title as project, p.status, COUNT(*) as count
            FROM postulations p
            JOIN projects pr ON p.project_id = pr.id
            GROUP BY pr.title, p.status
        ");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['project']][$row['status']] = (int)$row['count'];
        }
        return $result;
    }
} 