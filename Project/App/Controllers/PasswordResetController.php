<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\MailService; // Assure-toi que ta méthode d'envoi d'e-mails est prête.

class PasswordResetController {
    public function showForm() {
        return view('password-reset.request')->layout("guest");
    }

    public function sendResetLink() {
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
      return view("password-reset.reset")->layout("guest");
    }

    public function resetPassword() {
      $token = $_POST['token'];
      $newPassword = $_POST['password'];
  
      // Vérifier si le jeton est valide
      $user = User::getByResetToken($token);
  
      if (!$user || new \DateTime() > $user->reset_token_expiration) {

          return view('password-reset.reset', ['error' => 'Le lien de réinitialisation a expiré ou est invalide.'])->layout("guest");
      }
  
      // Hacher le mot de passe et mettre à jour dans la base de données
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      $user->updatePassword($hashedPassword);

      return view('login.index', ['success' => 'Votre mot de passe a été réinitialisé avec succès.'])->layout("guest");
  }
}