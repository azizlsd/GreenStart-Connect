<?php
require_once 'config.php';
require_once 'Controller/ProjectController.php';

// Get database connection
$db = config::getConnexion();

// Get controller and action from URL parameters
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Route to appropriate controller
switch ($controller) {
    case 'project':
        $projectController = new ProjectController($db);
        switch ($action) {
            case 'index':
                $projectController->index();
                break;
            case 'create':
                $projectController->create();
                break;
            case 'edit':
                if ($id === null) {
                    header('Location: index.php?controller=project&action=index');
                    exit;
                }
                $projectController->edit($id);
                break;
            case 'delete':
                if ($id === null) {
                    header('Location: index.php?controller=project&action=index');
                    exit;
                }
                $projectController->delete($id);
                break;
            default:
                header('Location: index.php?controller=project&action=index');
                break;
        }
        break;
    default:
        // Handle default route or 404
        require_once 'View/home.php';
        break;
}
?> 