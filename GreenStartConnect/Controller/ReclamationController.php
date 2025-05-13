<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

# require '../../vendor/autoload.php'; // Temporarily commented out to allow page loading without PHPMailer dependencies

class ReclamationController {
    public function index() {
        require_once __DIR__ . '/../View/admin/reclamations/create.php';
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Set your SMTP server here
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com'; // SMTP username
                $mail->Password = 'your_password'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS also possible
                $mail->Port = 587; // TCP port to connect to

                //Recipients
                $mail->setFrom($_POST['email'], $_POST['nom']);
                $mail->addAddress('destinataire@exemple.com'); // Add a recipient

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Nouvelle réclamation';
                $body = "Nom: " . $_POST['nom'] . "\n";
                $body .= "Email: " . $_POST['email'] . "\n";
                $body .= "Message: " . $_POST['message'] . "\n";
                $mail->Body = $body;

                $mail->send();
                $success = true;
            } catch (Exception $e) {
                $success = false;
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
            require_once __DIR__ . '/../View/admin/reclamations/create.php';
        }
    }
}
?>
