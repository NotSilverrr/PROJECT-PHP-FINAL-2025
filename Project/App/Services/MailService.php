<?php 
namespace App\Services;

class MailService {
    public function sendResetPasswordEmail($email, $resetLink) {
        // Exemple avec Resend
        $apiKey = $_ENV['RESEND_API_KEY'];
        $subject = "Réinitialisation de votre mot de passe";
        $htmlContent = "<p>Pour réinitialiser votre mot de passe, cliquez sur le lien suivant :</p><p><a href='$resetLink'>$resetLink</a></p>";

        $data = [
            'from' => $_ENV["RESEND_SENDER_MAIL"],
            'to' => $email,
            'subject' => $subject,
            'html' => $htmlContent
        ];

        $ch = curl_init("https://api.resend.com/emails");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // Gérer la réponse si nécessaire (erreur, succès...)
    }
}