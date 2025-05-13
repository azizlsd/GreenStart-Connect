<?php
require_once __DIR__ . '/../Model/Client.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ClientController
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'];
            $password = $_POST['mot_de_passe'];

            $client = new Client();
            $user = $client->findByEmail($email);

            if ($user) {
                // Check if the user is banned
                if (!empty($user['banned']) && $user['banned'] == 1) {
                    $error = "⚠️ Votre compte a été banni. Contactez un administrateur.";
                    include __DIR__ . '/../View/BackOffice/pages/login.php';
                    return;
                }

                // Check password
                if (password_verify($password, $user['mot_de_passe'])) {
                    $_SESSION['client'] = $user;

                    if ($user['role'] === 'admin') {
                        header("Location: index.php?action=dashboard");
                    } else {
                        header("Location: index.php?action=profil");
                    }
                    exit();
                }
            }

            // If no match or password incorrect
            $error = "❌ Email ou mot de passe incorrect.";
            include __DIR__ . '/../View/BackOffice/pages/login.php';
            return;
        }

        // GET request → Show login form
        include __DIR__ . '/../View/BackOffice/pages/login.php';
    }


    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'telephone' => $_POST['telephone'],
                'adresse' => $_POST['adresse'],
                'mot_de_passe' => $_POST['mot_de_passe'],
                'role' => 'client'
            ];

            $client = new Client();
            $result = $client->create($data);

            if ($result === "email_exists") {
                $error = "Cet email est déjà utilisé.";
            } elseif ($result === "success") {
                $user = $client->findByEmail($data["email"]);
                $_SESSION['client'] = $user;
                header("Location: index.php?action=profil");
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
        include __DIR__ . '/../View/BackOffice/pages/register.php';
    }

    public function dashboard()
    {
        if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'admin') {
            echo "Accès refusé. Administrateurs uniquement.";
            exit();
        }

        $client = new Client();
        $clients = $client->getAll();
        include 'C:\xampp\htdocs\GreenStart-Connect-main\GreenStartConnect\View\BackOffice\pages\usersList.php';
    }

    public function profil()
    {
        if (!isset($_SESSION['client']) || $_SESSION['client']['role'] !== 'client') {
            echo "Accès refusé. Clients uniquement.";
            exit();
        }

        include 'C:\xampp\htdocs\GreenStart-Connect-main\GreenStartConnect\View\FrontOffice\profil.php';
    }
    public function usersList()
    {
        $client = new Client();

        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $clients = $client->searchByName($search);
        } else {
            $clients = $client->getAll()->fetchAll();
        }

        // ✅ Statistiques
        $totalClients = $client->countAll();
        $totalBanned = $client->countBanned();
        $totalAdmins = $client->countAdmins();

        // ✅ Passage à la vue
        include __DIR__ . '/../View/BackOffice/pages/usersList.php';
    }



    public function edit()
    {
        $client = new Client();
        $id = $_GET['id'] ?? null;
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Contrôle de saisie
            $email = $_POST['email'] ?? '';
            $telephone = $_POST['telephone'] ?? '';
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email est invalide.";
            }

            if (!preg_match('/^\d{8}$/', $telephone)) {
                $errors[] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
            }

            if (strlen($nom) < 2 || strlen($prenom) < 2) {
                $errors[] = "Le nom et le prénom doivent contenir au moins 2 caractères.";
            }

            if (empty($errors)) {
                $client->update($id, $_POST);
                header("Location: index.php?action=dashboard");
                exit();
            }

            $data = $_POST; // pour remplir le formulaire avec les valeurs modifiées
        } else {
            $data = $client->findById($id);
        }

        include 'C:\xampp\htdocs\GreenStart-Connect-main\GreenStartConnect\View\BackOffice\pages\userUpdate.php';
    }

    public function editUser()
    {
        $client = new Client();
        $id = $_GET['id'] ?? null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $client->update($id, $_POST);
            $email = $_POST['email'];
            $user = $client->findByEmail($email);
            $_SESSION['client'] = $user;
            header("Location: index.php?action=profil");
            exit();
        }



    }
    public function delete()
    {
        $client = new Client();
        $client->delete($_GET['id']);
        header("Location: index.php?action=dashboard");
        exit();
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
    public function ban($id)
    {
        $client = new Client();
        $client->banUser($id);
        $_SESSION['flash'] = "✅ Utilisateur banni.";
        header("Location: index.php?action=usersList");
        exit();
    }

    public function unban($id)
    {
        $client = new Client();
        $client->unbanUser($id);
        $_SESSION['flash'] = "✅ Utilisateur débanni.";
        header("Location: index.php?action=usersList");
        exit();
    }




}