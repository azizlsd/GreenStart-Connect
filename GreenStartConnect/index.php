<?php
session_start();
require_once __DIR__ . '/Controller\ClientController.php';
require_once __DIR__ . '/controller/PostController.php';
require_once __DIR__ . '/controller/CommentaireController.php';
$clientController = new ClientController();
$postController = new PostController();
$commentController = new CommentaireController();
$action = $_GET['action'] ?? 'login';
$id = $_GET['id'] ?? null;

// Router logic
$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($controller === 'project') {
    require_once __DIR__ .'/controller/ProjectController.php';
    $projectController = new ProjectController();
    
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
    require_once __DIR__ .'/controller/ProjectExportController.php';
    $projectExportController = new ProjectExportController();
    
    switch ($action) {
        case 'exportPDF':
            $projectExportController->exportPDF();
            break;
        default:
            header('Location: index.php?controller=project');
            exit;
    }
} elseif ($controller === 'dashboard') {
    require_once __DIR__ . '/controller/DashboardController.php';
    $dashboardController = new DashboardController();
    
    switch ($action) {
        case 'index':
            $dashboardController->index();
            exit;
    }
} elseif ($controller === 'offer') {
    require_once __DIR__ . '/controller/OfferController.php';
    $offerController = new OfferController();
    
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
    require_once __DIR__ . '/controller/BalanceController.php';
    $balanceController = new BalanceController();
    
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
    require_once __DIR__ . '/controller/PostulationController.php';
    $postulationController = new PostulationController();
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
    require_once __DIR__ . '/controller/ReclamationController.php';
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
// Vérifie si la méthode existe
if (method_exists($clientController, $action)) {
    // Si l'action attend un ID (edit/delete/ban/unban), on le passe
    if (in_array($action, ['edit', 'delete', 'ban', 'unban'])) {
        $clientController->$action($id);
    } else {
        $clientController->$action();
    }
}
else if (method_exists($postController, $action)) {
    switch ($_GET['action']) {
        case 'ManagePosts':
            $postController->ManagePosts();
            break;
        case 'editPosts':
          
            $postController->editPosts($_GET['id']);
            break;
        case 'deletePosts':
            
            $postController->deletePosts($_GET['id']);
            break;
         case 'updatePosts':
                
                $postController->updatePosts();
                break;
    }
} else if (method_exists($commentController, $action)) {

    // Handle GET for fetchcomments
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'fetchcomments') {
        $postId = $_GET['fetch_comments'] ?? null;
        if ($postId !== null) {
            $commentController->fetchComments($postId);
        } else {
            echo json_encode(['error' => 'Missing post ID']);
        }
        exit;
    }
        switch ($_GET['action']) {
            case 'Manageblog':
                $commentController->Manageblog();
                break;
            case 'addcomment':
                $commentController->addComment($_POST);
                break;
            case 'ratecomment':
                $commentController->rateComment((int)$_POST['id_comment'], $_POST['type']);
                break;
            case 'updatecomment':
                $commentController->updateComment($_POST);
                break;
            case 'deletecomment':
                $commentController->deleteComment($_GET['id']);
                break;
        
        exit;
    }





}
 else {
     switch ($_GET['action']) {
            case 'feedbackadmin':
                include __DIR__ . '/View/BackOffice/pages/feedback.php';
                break;
            case 'feedbackuser':
               include __DIR__ . '/View/FrontOffice/feedback.php';
                break;
        exit;
    }
   
}