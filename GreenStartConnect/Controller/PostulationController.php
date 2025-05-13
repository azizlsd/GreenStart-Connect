<?php
require_once __DIR__ . '/../Model/Postulation.php';
require_once __DIR__ . '/../Model/Project.php';

class PostulationController {
    private $postulationModel;
    private $projectModel;

    public function __construct() {
        $this->postulationModel = new Postulation();
        $this->projectModel = new Project();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] === 'date_desc' ? 'date_desc' : 'date_asc';

        if ($search !== '') {
            $postulations = $this->postulationModel->search($search, $sort);
        } else {
            $postulations = $this->postulationModel->getAll($sort);
        }
       require_once __DIR__ . '/../View/admin/postulations/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'project_id' => $_POST['project_id'],
                'feedback' => $_POST['feedback']
            ];

            if ($this->postulationModel->create($data)) {
                header('Location: index.php?controller=postulation&action=index');
                exit;
            }
        }
        
        $projects = $this->projectModel->getAll();
       require_once __DIR__ . '/../View/admin/postulations/create.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=postulation&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'project_id' => $_POST['project_id'],
                'feedback' => $_POST['feedback']
            ];

            if ($this->postulationModel->update($id, $data)) {
                header('Location: index.php?controller=postulation&action=index');
                exit;
            }
        }

        $postulation = $this->postulationModel->getById($id);
        $projects = $this->projectModel->getAll();
       require_once __DIR__ . '/../View/admin/postulations/edit.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id && $this->postulationModel->delete($id)) {
            header('Location: index.php?controller=postulation&action=index');
            exit;
        }
    }

    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=postulation&action=index');
            exit;
        }

        $postulation = $this->postulationModel->getById($id);
       require_once __DIR__ . '/../View/admin/postulations/view.php';
    }
}
?> 