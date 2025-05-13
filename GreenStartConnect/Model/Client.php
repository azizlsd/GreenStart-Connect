<?php
require_once __DIR__ . '/../config/config.php';

class Client
{
    private $conn;
    private $table = "clients";

    public function __construct()
    {
        $db = new Config();
        $this->conn = $db->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY date_creation DESC");
        $stmt->execute();
        return $stmt;
    }
    public function searchByName($name)
    {
        $searchTerm = '%' . $name . '%';
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE nom LIKE :search OR prenom LIKE :search ORDER BY date_creation DESC");
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Vérification email valide
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "invalid_email";
        }

        // Vérification mot de passe fort (min. 6 caractères ici)
        if (strlen($data['mot_de_passe']) < 6) {
            return "weak_password";
        }

        // Vérifier si l'email existe déjà
        $check = $this->conn->prepare("SELECT id FROM $this->table WHERE email = :email");
        $check->bindParam(":email", $data['email']);
        $check->execute();

        if ($check->rowCount() > 0) {
            return "email_exists"; // ⚠️ email déjà utilisé
        }

        // Hachage du mot de passe
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $stmt = $this->conn->prepare("INSERT INTO $this->table (nom, prenom, email, telephone, adresse, mot_de_passe, role) 
            VALUES (:nom, :prenom, :email, :telephone, :adresse, :mot_de_passe, :role)");

        return $stmt->execute($data) ? "success" : "error";
    }


    public function countAll()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM clients");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countBanned()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM clients WHERE banned = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countAdmins()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM clients WHERE role = 'admin'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE $this->table SET nom=:nom, prenom=:prenom, email=:email, telephone=:telephone, adresse=:adresse WHERE id=:id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Ban user
    public function banUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE $this->table SET banned = 1 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Unban user
    public function unbanUser($id)
    {
        $stmt = $this->conn->prepare("UPDATE $this->table SET banned = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


}