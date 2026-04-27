<?php

namespace app\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function sendWelcome(string $to, string $firstName, string $password, string $memberNum): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->Port       = MAIL_PORT;
            $mail->SMTPAuth   = false;
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to, $prenom);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue sur ' . APP_NAME . ' — vos identifiants';
            $mail->Body    = self::welcomeTemplate($firstName, $to, $password, $memberNum);
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer::sendBienvenue — ' . $mail->ErrorInfo);
            return false;
        }
    }

    private static function welcomeTemplate(string $firstName, string $mail, string $password, string $memberNum): string {
        return '
        <p>Bonjour <strong>' . htmlspecialchars($firstName) . '</strong>,</p>
        <p>Votre compte sur <strong>' . APP_NAME . '</strong> vient d\'être créé.</p>
        <table cellpadding="6" style="border-collapse:collapse;">
            <tr><td><strong>Email</strong></td><td>' . htmlspecialchars($mail) . '</td></tr>
            <tr><td><strong>Mot de passe</strong></td><td>' . htmlspecialchars($password) . '</td></tr>
            <tr><td><strong>N° adhérent</strong></td><td>' . htmlspecialchars($memberNum) . '</td></tr>
        </table>
        <p style="margin-top:16px;color:#888;font-size:0.9em;">Pensez à modifier votre mot de passe après votre première connexion.</p>
        ';
    }
}
