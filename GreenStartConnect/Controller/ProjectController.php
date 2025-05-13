<?php
require_once __DIR__ .'/../Model/Project.php';
require_once __DIR__ . '/../config/config.php';
class ProjectController {
    private $projectModel;
    private $db;

    public function __construct() {
        $this->projectModel = new Project();
    }

    public function index() {
        $projects = $this->projectModel->getAllProjects();
       require_once __DIR__ . '/../View/projects/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => $_POST['status'],
                'budget' => $_POST['budget']
            ];

            if ($this->projectModel->createProject($data)) {
                header('Location: index.php?controller=project&action=index&success=create');
                exit;
            }
        }
       require_once __DIR__ . '/../View/projects/create.php';
    }

    public function edit($id) {
        $project = $this->projectModel->getProjectById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => $_POST['status'],
                'budget' => $_POST['budget']
            ];

            if ($this->projectModel->updateProject($id, $data)) {
                header('Location: index.php?controller=project&action=index&success=update');
                exit;
            }
        }
        
        if (!$project) {
            header('Location: index.php?controller=project&action=index&error=notfound');
            exit;
        }
        
       require_once __DIR__ . '/../View/projects/edit.php';
    }

    public function delete($id) {
        if ($this->projectModel->deleteProject($id)) {
            header('Location: index.php?controller=project&action=index&success=delete');
        } else {
            header('Location: index.php?controller=project&action=index&error=delete');
        }
        exit;
    }
}
?> 