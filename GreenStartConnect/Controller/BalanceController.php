<?php
require_once __DIR__ . '/../Model/Project.php';
require_once __DIR__ . '/../config/config.php';
class BalanceController {
    private $pdo;

    public function __construct() {
        
        $dbC = new Config();
        $this->pdo = $dbC->getConnection();
        $this->initializeTable();
    }

    private function initializeTable() {
        try {
            // Vérifier si la table existe déjà
            $tableExists = $this->pdo->query("SHOW TABLES LIKE 'transfers'")->rowCount() > 0;
            
            if (!$tableExists) {
                // Créer la table seulement si elle n'existe pas
                $sql = "CREATE TABLE transfers (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    rib VARCHAR(23) NOT NULL,
                    montant DECIMAL(10,2) NOT NULL,
                    description TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                
                $this->pdo->exec($sql);
                error_log("Table transfers créée avec succès");
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification/création de la table transfers: " . $e->getMessage());
            throw $e;
        }
    }

    public function index() {
        $stmt = $this->pdo->query("SELECT SUM(budget) as total_budget FROM projects");
        $totalBudget = $stmt->fetch(PDO::FETCH_ASSOC)['total_budget'] ?: 0;
        require_once __DIR__ . '/../View/admin/balance.php';
    }

    public function history() {
        try {
            
            
            // Debug: Vérifier l'appel de la méthode
            error_log("Méthode history() appelée");
            
            // Vérifier la connexion
            if (!$this->pdo) {
                error_log("ERREUR: Pas de connexion à la base de données");
                throw new Exception("La connexion à la base de données n'est pas établie.");
            }

            // Vérifier si la table existe
            $tableExists = $this->pdo->query("SHOW TABLES LIKE 'transfers'")->rowCount() > 0;
            if (!$tableExists) {
                error_log("La table transfers n'existe pas, création...");
                $this->initializeTable();
            }

            try {
                // Récupérer tous les transferts avec ORDER BY pour avoir les plus récents en premier
                $query = "SELECT * FROM transfers ORDER BY created_at DESC";
                error_log("Exécution de la requête: " . $query);
                
                $stmt = $this->pdo->query($query);
                if ($stmt === false) {
                    error_log("ERREUR: La requête a échoué");
                    throw new Exception("Erreur lors de la récupération des transferts.");
                }

                $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $count = count($transfers);
                
                error_log("Nombre de transferts récupérés: " . $count);
                if ($count > 0) {
                    error_log("Premier transfert: " . print_r($transfers[0], true));
                }

                // Récupérer le solde total des projets
                $stmtBalance = $this->pdo->query("SELECT SUM(budget) as total_budget FROM projects");
                $result = $stmtBalance->fetch(PDO::FETCH_ASSOC);
                $totalBudget = $result['total_budget'] ?? 0;
                
                error_log("Total budget: " . $totalBudget);

                // Passer les données à la vue
                require_once __DIR__ . '/../View/admin/transfer_history.php';
                
            } catch (PDOException $e) {
                error_log("ERREUR PDO dans history(): " . $e->getMessage());
                throw new Exception("Erreur lors de la récupération des données: " . $e->getMessage());
            }

        } catch (Exception $e) {
            error_log("ERREUR dans history(): " . $e->getMessage());
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: index.php?controller=balance&action=index');
            exit;
        }
    }

    public function transfer() {
        try {
            session_start();
            error_log("Début de la méthode transfer()");

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode non autorisée");
            }

            // Vérifier la connexion à la base de données
            if (!$this->pdo) {
                throw new Exception("La connexion à la base de données n'est pas établie");
            }

            // Récréer la table pour s'assurer qu'elle a la bonne structure
            $this->initializeTable();

            // Récupérer et nettoyer les données
            $rib = trim($_POST['rib'] ?? '');
            $montant = floatval($_POST['amount'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            error_log("Données reçues - RIB: $rib, Montant: $montant, Description: $description");

            // Validation
            if (empty($rib) || strlen($rib) !== 23) {
                throw new Exception("Le RIB doit contenir exactement 23 chiffres");
            }
            if ($montant <= 0) {
                throw new Exception("Le montant doit être supérieur à 0");
            }
            if (empty($description)) {
                throw new Exception("La description est obligatoire");
            }

            // Démarrer la transaction
            $this->pdo->beginTransaction();
            error_log("Transaction démarrée");

            try {
                // Insérer d'abord le transfert
                $insertQuery = "INSERT INTO transfers (rib, montant, description) VALUES (?, ?, ?)";
                error_log("Query d'insertion: " . $insertQuery);
                
                $insertStmt = $this->pdo->prepare($insertQuery);
                if (!$insertStmt) {
                    throw new Exception("Erreur lors de la préparation de la requête d'insertion");
                }

                $insertResult = $insertStmt->execute([$rib, $montant, $description]);
                if (!$insertResult) {
                    $error = $insertStmt->errorInfo();
                    error_log("Erreur lors de l'insertion: " . print_r($error, true));
                    throw new Exception("Erreur lors de l'insertion du transfert");
                }

                $transferId = $this->pdo->lastInsertId();
                error_log("Transfert inséré avec ID: " . $transferId);

                // Vérifier que l'insertion a réussi
                $checkQuery = "SELECT * FROM transfers WHERE id = ?";
                $checkStmt = $this->pdo->prepare($checkQuery);
                $checkStmt->execute([$transferId]);
                $transfer = $checkStmt->fetch();

                if (!$transfer) {
                    throw new Exception("Le transfert n'a pas été correctement enregistré");
                }

                error_log("Transfert vérifié: " . print_r($transfer, true));

                // Mettre à jour le budget du projet
                $projectQuery = "SELECT id, budget FROM projects WHERE budget > 0 ORDER BY budget DESC LIMIT 1";
                $projectStmt = $this->pdo->query($projectQuery);
                $project = $projectStmt->fetch();

                if (!$project) {
                    throw new Exception("Aucun projet avec un budget suffisant n'a été trouvé");
                }

                $newBudget = $project['budget'] - $montant;
                if ($newBudget < 0) {
                    throw new Exception("Budget insuffisant");
                }

                $updateQuery = "UPDATE projects SET budget = ? WHERE id = ?";
                $updateStmt = $this->pdo->prepare($updateQuery);
                $updateResult = $updateStmt->execute([$newBudget, $project['id']]);

                if (!$updateResult) {
                    $error = $updateStmt->errorInfo();
                    error_log("Erreur lors de la mise à jour du budget: " . print_r($error, true));
                    throw new Exception("Erreur lors de la mise à jour du budget");
                }

                // Valider la transaction
                $this->pdo->commit();
                error_log("Transaction validée avec succès");

                $_SESSION['success'] = "Le transfert de " . number_format($montant, 2, ',', ' ') . " € a été effectué avec succès";
                header('Location: index.php?controller=balance&action=history');
                exit;

            } catch (Exception $e) {
                error_log("Erreur dans la transaction: " . $e->getMessage());
                $this->pdo->rollBack();
                throw $e;
            }

        } catch (Exception $e) {
            error_log("Erreur dans transfer(): " . $e->getMessage());
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: index.php?controller=balance&action=index');
            exit;
        }
    }
} 