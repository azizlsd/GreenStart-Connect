<?php
require_once __DIR__ . '/../Model/Project.php';
require_once __DIR__ . '/../config/config.php';

class AdminController {
    private $projectModel;
    private $pdo;

    public function __construct() {
       $dbC = new Config();
        $this->pdo = $dbC->getConnection();
        $this->projectModel = new Project();
    }

    public function balance() {
        // Get total budget from all projects
        $totalBudget = $this->projectModel->getTotalBudget();
        
        // Include the balance view
        require_once __DIR__ . '/../View/admin/balance.php';
    }

    public function processTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=balance');
            exit;
        }

        $rib = $_POST['rib'] ?? '';
        $amount = floatval($_POST['amount'] ?? 0);
        $description = $_POST['description'] ?? '';

        // Validate RIB format (23 digits)
        if (!preg_match('/^\d{23}$/', $rib)) {
            $_SESSION['error'] = "Le format du RIB est invalide. Il doit contenir exactement 23 chiffres.";
            header('Location: index.php?controller=admin&action=balance');
            exit;
        }

        // Validate amount
        if ($amount <= 0) {
            $_SESSION['error'] = "Le montant du transfert doit être supérieur à 0.";
            header('Location: index.php?controller=admin&action=balance');
            exit;
        }

        // Get current total budget
        $totalBudget = $this->projectModel->getTotalBudget();

        // Check if there are sufficient funds
        if ($amount > $totalBudget) {
            $_SESSION['error'] = "Fonds insuffisants pour effectuer ce transfert.";
            header('Location: index.php?controller=admin&action=balance');
            exit;
        }

        // TODO: Implement actual transfer logic here
        // This would typically involve:
        // 1. Connecting to a payment gateway
        // 2. Processing the transfer
        // 3. Recording the transaction in a transfers table
        // For now, we'll just show a success message

        $_SESSION['success'] = "Transfert de " . number_format($amount, 2, ',', ' ') . " € vers le RIB " . 
                             chunk_split($rib, 4, ' ') . " effectué avec succès.";
        
        header('Location: index.php?controller=admin&action=balance');
        exit;
    }
} 