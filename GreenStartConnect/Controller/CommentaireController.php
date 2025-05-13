<?php
require_once __DIR__ . '/../config/config.php';

class CommentaireController {
    private $pdo;

    public function __construct() {
        $db = new Config();
        $this->pdo = $db->getConnection();
    }
    public function Manageblog()
    {
        $postController = new PostController();
        $posts = $postController->getAll();
    include __DIR__ . '/../View/FrontOffice/blog.php';
    return;
}

public function addComment($postData) {
    $errors = [];
    $id_post = filter_input(INPUT_POST, 'id_post', FILTER_VALIDATE_INT);
    $contenu = trim($postData['contenu'] ?? '');

    // Validation
    if (empty($contenu)) {
        $errors[$id_post][] = "Comment cannot be empty.";
    }

    $badWords = ['shit', 'fuck', 'fuck you']; // Add your bad words list
    foreach ($badWords as $word) {
        if (stripos($contenu, $word) !== false) {
            $errors[$id_post][] = "Comment contains inappropriate language.";
            break;
        }
    }

    if (!empty($errors)) {
        $_SESSION['comment_errors'] = $errors;
        header("Location: index.php?action=Manageblog");
        exit();
    }

    // If validation passes, save the comment
    try {
        $this->store([
            'id_post' => $id_post,
            'contenu' => htmlspecialchars($contenu, ENT_QUOTES, 'UTF-8'),
            'action' => 'add_comment'
        ]);
        header("Location: index.php?action=Manageblog");
        exit();
    } catch (Exception $e) {
        $_SESSION['comment_errors'][$id_post][] = "Error saving comment: " . $e->getMessage();
        header("Location: index.php?action=Manageblog");
        exit();
    }
    include __DIR__ . '/../View/FrontOffice/blog.php';
}

public function rateComment($commentId, $type) {
    if ($commentId > 0 && in_array($type, ['like', 'dislike'])) {
        $success = $this->rate($commentId, $type);
        if ($success) {
            // Get updated counts to return
            $stmt = $this->pdo->prepare("SELECT likes, dislikes FROM commentaire WHERE id_comment = ?");
            $stmt->execute([$commentId]);
            $counts = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'likes' => $counts['likes'], 'dislikes' => $counts['dislikes']]);
            exit;
        }
    }
    echo json_encode(['success' => false]);
    include __DIR__ . '/../View/FrontOffice/blog.php';
    return;
}

public function updateComment($postData) {
    if (isset($postData['id_comment']) && isset($postData['contenu'])) {
        $id_comment = $postData['id_comment'];
        $contenu = $postData['contenu'];

        if (!empty($id_comment) && !empty($contenu)) {
            // Call the update function
            $this->update([
                'id_comment' => $id_comment,
                'contenu' => $contenu
            ]);
        }
    }
    include __DIR__ . '/../View/FrontOffice/blog.php';
    return ;
}

public function deleteComment($commentId) {
    $this->delete($commentId);
    include __DIR__ . '/../View/FrontOffice/blog.php';
    return ;
}

public function fetchComments($postId) {
    $comments = $this->getByPost($postId);
    echo json_encode($comments);
    include __DIR__ . '/../View/FrontOffice/blog.php';

    return ;
}
    // Get all comments for a specific post (newest first)
    public function getByPost(int $id_post): array {
        $stmt = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_post = ? ORDER BY date_reponse DESC");
        $stmt->execute([$id_post]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Store a new comment
    public function store(array $data): void {
        $contenu = trim($data['contenu'] ?? '');
        $id_post = isset($data['id_post']) && is_numeric($data['id_post']) ? (int)$data['id_post'] : 0;

        if (empty($contenu) || $id_post <= 0) {
            die("Erreur: Le contenu du commentaire et l'identifiant du post sont requis.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO commentaire (contenu, date_reponse, id_post) VALUES (?, NOW(), ?)");
        $stmt->execute([$contenu, $id_post]);

        header("Location: index.php?action=Manageblog");
        exit;
    }

    // Get a single comment by ID
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_comment  = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Update an existing comment
    public function update(array $data): bool {
        $contenu = trim($data['contenu'] ?? '');
        $id_comment = isset($data['id_comment']) && is_numeric($data['id_comment']) ? (int)$data['id_comment'] : 0;
    
        if (empty($contenu) || $id_comment <= 0) {
            return false;
        }
    
        // Update the comment in the database
        $stmt = $this->pdo->prepare("UPDATE commentaire SET contenu = ? WHERE id_comment = ?");
        return $stmt->execute([$contenu, $id_comment]);
    }
    public function rate($id_comment, $type): bool {
        $column = $type === 'like' ? 'likes' : 'dislikes';
        $stmt = $this->pdo->prepare("UPDATE commentaire SET $column = $column + 1 WHERE id_comment = ?");
        return $stmt->execute([$id_comment]);
    }
        
    
    // Delete a comment by ID
    public function delete(int $id_comment ): bool {
        $stmt = $this->pdo->prepare("DELETE FROM commentaire WHERE id_comment= ?");
        return $stmt->execute([$id_comment]);
    }

    // (Optional) Get all comments (e.g., for admin)
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM commentaire ORDER BY date_reponse DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
