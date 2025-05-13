<?php
require_once __DIR__ . '/../Model/Offer.php';
require_once __DIR__ . '/../Model/Postulation.php';

class OfferController {
    private $offerModel;
    private $postulationModel;
    private $db;

    public function __construct() {
       
        $this->offerModel = new Offer();
        $this->postulationModel = new Postulation();
    }

    public function index() {
        $offers = $this->offerModel->getAllOffers();
        require_once __DIR__ . '/../View/offers/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'requirements' => $_POST['requirements'],
                'location' => $_POST['location'],
                'type' => $_POST['type'],
                'status' => 'Active'
            ];

            if ($this->offerModel->createOffer($data)) {
                header('Location: index.php?controller=offer&action=index');
                exit;
            }
        }
        require_once __DIR__ . '/../View/offers/create.php';
    }

    public function edit($id) {
        $offer = $this->offerModel->getOfferById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'requirements' => $_POST['requirements'],
                'location' => $_POST['location'],
                'type' => $_POST['type'],
                'status' => $_POST['status']
            ];

            if ($this->offerModel->updateOffer($id, $data)) {
                header('Location: index.php?controller=offer&action=index');
                exit;
            }
        }
        require_once __DIR__ . '/../View/offers/edit.php';
    }

    public function delete($id) {
        if ($this->offerModel->deleteOffer($id)) {
            header('Location: index.php?controller=offer&action=index');
            exit;
        }
    }

    public function view($id) {
        $offer = $this->offerModel->getOfferById($id);
        $postulations = $this->postulationModel->getPostulationsByOffer($id);
        require_once __DIR__ . '/../View/offers/view.php';
    }
}
?> 