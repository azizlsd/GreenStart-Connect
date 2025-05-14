<?php
// Inclure le fichier de configuration pour obtenir la connexion à la base de données
require_once 'config.php';

// Récupérer la connexion PDO via la méthode statique
$pdo = config::getConnexion();

// Router logic
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($controller === 'project') {
    require_once 'Controller/ProjectController.php';
    $projectController = new ProjectController($pdo);
    
    switch ($action) {
        case 'index':
            $projectController->index();
            exit;
        case 'create':
            $projectController->create();
            exit;
        case 'edit':
            if ($id !== null) {
                $projectController->edit($id);
                exit;
            }
            break;
        case 'delete':
            if ($id !== null) {
                $projectController->delete($id);
                exit;
            }
            break;
    }
} elseif ($controller === 'projectExport') {
    require_once 'Controller/ProjectExportController.php';
    $projectExportController = new ProjectExportController($pdo);
    
    switch ($action) {
        case 'exportPDF':
            $projectExportController->exportPDF();
            break;
        default:
            header('Location: index.php?controller=project');
            exit;
    }
} elseif ($controller === 'dashboard') {
    require_once 'Controller/DashboardController.php';
    $dashboardController = new DashboardController($pdo);
    
    switch ($action) {
        case 'index':
            $dashboardController->index();
            exit;
    }
} elseif ($controller === 'offer') {
    require_once 'Controller/OfferController.php';
    $offerController = new OfferController($pdo);
    
    switch ($action) {
        case 'index':
            $offerController->index();
            exit;
        case 'create':
            $offerController->create();
            exit;
        case 'edit':
            if ($id !== null) {
                $offerController->edit($id);
                exit;
            }
            break;
        case 'delete':
            if ($id !== null) {
                $offerController->delete($id);
                exit;
            }
            break;
        case 'view':
            if ($id !== null) {
                $offerController->view($id);
                exit;
            }
            break;
    }
} elseif ($controller === 'balance') {
    require_once 'Controller/BalanceController.php';
    $balanceController = new BalanceController($pdo);
    
    switch ($action) {
        case 'index':
            $balanceController->index();
            exit;
        case 'transfer':
            $balanceController->transfer();
            exit;
        case 'history':
            $balanceController->history();
            exit;
        default:
            $balanceController->index();
            exit;
    }
} elseif ($controller === 'postulation') {
    require_once 'Controller/PostulationController.php';
    $postulationController = new PostulationController($pdo);
    switch ($action) {
        case 'index':
            $postulationController->index();
            exit;
        case 'create':
            $postulationController->create();
            exit;
        case 'edit':
            if ($id !== null) {
                $_GET['id'] = $id; // pour compatibilité
                $postulationController->edit();
                exit;
            }
            break;
        case 'delete':
            if ($id !== null) {
                $_GET['id'] = $id; // pour compatibilité
                $postulationController->delete();
                exit;
            }
            break;
        case 'view':
            if ($id !== null) {
                $_GET['id'] = $id; // pour compatibilité
                $postulationController->view();
                exit;
            }
            break;
    }
} elseif ($controller === 'reclamation') {
    require_once 'Controller/ReclamationController.php';
    $reclamationController = new ReclamationController();
    switch ($action) {
        case 'index':
            $reclamationController->index();
            exit;
        case 'send':
            $reclamationController->send();
            exit;
    }
}

// Si aucun contrôleur n'est spécifié ou si c'est la page d'accueil
// Initialiser la variable events
$events = [];

// Vérifier si la table events existe
try {
    // Préparer et exécuter la requête pour récupérer les événements
    $query = "SELECT * FROM events";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si la table n'existe pas, on continue sans erreur
    if ($e->getCode() != '42S02') {
        // Si c'est une autre erreur, on la gère
        die('Erreur : ' . $e->getMessage());
    }
}

// Inclure la vue de la page d'accueil
include 'View/home.php';
