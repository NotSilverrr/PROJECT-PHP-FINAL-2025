<?php


namespace App\Controllers;


use App\Models\User;
use App\Requests\ResetPasswordRequest;
use App\Services\MailService;
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

        $user = User::findOneByEmail($email);
        if (!$user) {
            return view('password-reset.request', ['error' => 'Aucun utilisateur trouvé avec cet e-mail.'])->layout("guest");
        }

        $resetToken = bin2hex(random_bytes(32));

        $expiration = new \DateTime('+1 hour');
        $user->saveResetToken($resetToken, $expiration);

        $resetLink = $_ENV["HOST_NAME"]."/reset-password?token=$resetToken";

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