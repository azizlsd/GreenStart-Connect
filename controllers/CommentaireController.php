<?php
require_once dirname(__FILE__) . '/../config/db.php';

class CommentaireController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all comments for a specific post (newest first)
    public function getByPost($id_post) {
        $id_post = (int)$id_post;
        $stmt = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_post = ? ORDER BY date_reponse DESC");
        $stmt->execute(array($id_post));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Store a new comment
    public function store($data) {
        $contenu = isset($data['contenu']) ? trim($data['contenu']) : '';
        $id_post = isset($data['id_post']) && is_numeric($data['id_post']) ? (int)$data['id_post'] : 0;

        if (empty($contenu) || $id_post <= 0) {
            die("Erreur: Le contenu du commentaire et l'identifiant du post sont requis.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO commentaire (contenu, date_reponse, id_post) VALUES (?, NOW(), ?)");
        $stmt->execute(array($contenu, $id_post));

        header("Location: blog.php");
        exit;
    }

    // Get a single comment by ID
    public function getById($id) {
        $id = (int)$id;
        $stmt = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_comment = ?");
        $stmt->execute(array($id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    // Update an existing comment
    public function update($data) {
        $contenu = isset($data['contenu']) ? trim($data['contenu']) : '';
        $id_comment = isset($data['id_comment']) && is_numeric($data['id_comment']) ? (int)$data['id_comment'] : 0;
    
        if (empty($contenu) || $id_comment <= 0) {
            return false;
        }
    
        // Update the comment in the database
        $stmt = $this->pdo->prepare("UPDATE commentaire SET contenu = ? WHERE id_comment = ?");
        return $stmt->execute(array($contenu, $id_comment));
    }

    public function rate($id_comment, $type) {
        $column = $type === 'like' ? 'likes' : 'dislikes';
        $stmt = $this->pdo->prepare("UPDATE commentaire SET $column = $column + 1 WHERE id_comment = ?");
        return $stmt->execute(array($id_comment));
    }
        
    // Delete a comment by ID
    public function delete($id_comment) {
        $id_comment = (int)$id_comment;
        $stmt = $this->pdo->prepare("DELETE FROM commentaire WHERE id_comment = ?");
        return $stmt->execute(array($id_comment));
    }

    // (Optional) Get all comments (e.g., for admin)
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM commentaire ORDER BY date_reponse DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}