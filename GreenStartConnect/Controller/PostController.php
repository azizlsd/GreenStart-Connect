
<?php
require_once __DIR__ . '/../config/config.php';

class PostController {
    private $pdo;

    public function __construct() {
        $db = new Config();
        $this->pdo = $db->getConnection();
        
    }

    public function updatePosts() {
     
            // Handle image upload for update
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../View/' . $imagePath);
            } else {
                $imagePath = $_POST['existing_image']; // Use the existing image path
            }
    
            // Prepare data for updating the post
            $data = [
                'questions' => $_POST['questions'],
                'date_creation' => $_POST['date_creation'], // Keep the existing date
                'id_user' => 1, // Adjust this according to the logged-in user
                'type' => $_POST['type'],
                'imagePath' => $imagePath
            ];
    
            $this->update($_POST['id_post'], $data);
            header("Location: index.php?action=ManagePosts");
            exit;
        
        

        include __DIR__ . '/../View/BackOffice/pages/Posts.php';
        return;
    }
    public function deletePosts($id) {
        $editing = $this->delete($id);

        include __DIR__ . '/../View/BackOffice/pages/Posts.php';
        return;
    }

    public function editPosts($id) {
        $editing = $this->getById($id);
        $posts = $this->index();
        include __DIR__ . '/../View/BackOffice/pages/Posts.php';
        return;
    }
    public function ManagePosts()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'add') {
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../View/' . $imagePath);
            } else {
                $imagePath = null; // Set default imagePath or handle accordingly
            }
    
            // Prepare data for creating the post
            $data = [
                'questions' => $_POST['questions'],
                'date_creation' => date('Y-m-d H:i:s'), // Current timestamp
                'id_user' => 1, // Assuming user ID is 1 for demonstration, adjust as needed
                'type' => $_POST['type'],
                'imagePath' => $imagePath
            ];
            $this->store($data);
           
            header("Location: index.php?action=ManagePosts");
            exit;
        } 
    }
    // GET request → Show login form
    include __DIR__ . '/../View/BackOffice/pages/Posts.php';
    return;
}
    // Get all posts
    public function index(): array {
        $stmt = $this->pdo->query("SELECT * FROM post ORDER BY date_creation DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one post by ID
    public function getById($id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM post WHERE id_post = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        return $post ?: null;
    }
    public function getByPost(int $id_post): array {
        $stmt = $this->pdo->prepare("SELECT * FROM commentaire WHERE id_post = ? ORDER BY date_reponse DESC");
        $stmt->execute([$id_post]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Create a new post
public function store(array $data): void {
    if (!isset($data['questions'], $data['date_creation'], $data['id_user'], $data['type'], $data['imagePath'])) {
        throw new InvalidArgumentException("Missing required fields");
    }
    $stmt = $this->pdo->prepare("INSERT INTO post (questions, date_creation,type, imagePath) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['questions'],
        $data['date_creation'],
        $data['type'],
        $data['imagePath']
    ]);
}


    // Update a post
    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare("UPDATE post SET questions = ?, date_creation = ?,type = ?, imagePath = ? WHERE id_post = ?");
        $stmt->execute([
            $data['questions'],
            $data['date_creation'],
            $data['type'],
            $data['imagePath'],
            $id
        ]);
    }

    // Delete a post
    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM post WHERE id_post = ?");
        $stmt->execute([$id]);
    }

    // Get all posts (This method is redundant with index)
    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM post");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
