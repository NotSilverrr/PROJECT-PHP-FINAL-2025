<?php


namespace App\Controllers;


use App\Models\User;
use App\Requests\ResetPasswordRequest;
use App\Services\MailService; // Assure-toi que ta méthode d'envoi d'e-mails est prête.
use App\Services\RegisterService;
use App\Services\ResetPasswordService;

class PasswordResetController {
    public function showForm() {
        startSession();
        return view('password-reset.request')->layout("guest");
    }

    public function sendResetLink() {
        startSession();
        $email = $_POST['email'];

        // Vérifier si l'utilisateur existe
        $user = User::findOneByEmail($email);
        if (!$user) {
            // L'email n'est pas dans la base de données
            return view('password-reset.request', ['error' => 'Aucun utilisateur trouvé avec cet e-mail.'])->layout("guest");
        }

        // Créer un jeton unique pour cette réinitialisation
        $resetToken = bin2hex(random_bytes(32)); // Créer un jeton unique

        // Sauvegarder le jeton et la date d'expiration dans la base de données
        $expiration = new \DateTime('+1 hour'); // Le jeton expire après 1 heure
        $user->saveResetToken($resetToken, $expiration);

        // Créer le lien de réinitialisation
        $resetLink = $_ENV["HOST_NAME"]."/reset-password?token=$resetToken";

        // Envoyer un e-mail à l'utilisateur avec le lien
        $mailService = new MailService();
        $mailService->sendResetPasswordEmail($email, $resetLink);

        return view('password-reset.request', ['success' => 'Un e-mail de réinitialisation a été envoyé.'])->layout("guest");
    }

    public function showResetForm() {
        startSession();
        return view("password-reset.reset")->layout("guest");
    }

    public function resetPassword() {
        startSession();
        $request = new ResetPasswordRequest();
        $token = $request->token;
        $newPassword = $request->password;
        $confirmPassword = $request->password_check;



        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'The passwords do not match.';
            header("Location: /reset-password?token=$token");
            exit;
        }

        $service = new ResetPasswordService($request);

        $error = $service->validate_password();
        if ($error !== null) {
            $_SESSION['error'] = $error;
            header("Location: /reset-password?token=$token");
            exit;
        }


        $error = $service->validate_password_check();
        if ($error !== null) {
            $_SESSION['error'] = $error;
            header("Location: /reset-password?token=$token");
            exit;
        }
    
        $user = User::getByResetToken($token);
    
        if (!$user || new \DateTime() > $user->reset_token_expiration) {
            $_SESSION['error'] = 'Le lien de réinitialisation a expiré ou est invalide.';
            header("Location: /login");
            exit;
        }
    
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->updatePassword($hashedPassword);

        $_SESSION['success'] = 'Votre mot de passe a été réinitialisé avec succès.';
        header("Location: /login");
        exit;
  }
}