<?php

namespace app\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Utilitaire d'envoi d'emails via SMTP (PHPMailer).
 *
 * La configuration SMTP (hôte, port, identifiants) est centralisée
 * dans les constantes MAIL_* de app/param.php.
 * Si MAIL_USER est vide, l'authentification SMTP est désactivée (usage local/Docker).
 */
class Mailer {

    /**
     * Envoie un email de bienvenue à un utilisateur nouvellement créé
     * avec ses identifiants de connexion.
     * En cas d'échec, l'erreur est loggée sans bloquer l'appelant.
     *
     * @param string $to        Adresse email du destinataire.
     * @param string $firstName Prénom affiché dans le corps du mail.
     * @param string $password  Mot de passe en clair généré à la création.
     * @param string $memberNum Numéro adhérent généré à la création.
     */
    public static function sendWelcome(string $to, string $firstName, string $password, string $memberNum): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host        = MAIL_HOST;
            $mail->Port        = MAIL_PORT;
            // Authentification désactivée si aucun identifiant configuré (ex : Mailpit local)
            $mail->SMTPAuth    = !empty(MAIL_USER);
            if (!empty(MAIL_USER)) {
                $mail->Username = MAIL_USER;
                $mail->Password = MAIL_PASS;
            }
            // SSL uniquement sur le port 465 ; TLS automatique désactivé pour les serveurs locaux
            $mail->SMTPSecure  = MAIL_PORT === 465 ? PHPMailer::ENCRYPTION_SMTPS : '';
            $mail->SMTPAutoTLS = false;
            $mail->CharSet     = 'UTF-8';
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to, $firstName);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue sur ' . APP_NAME . ' — vos identifiants';
            $mail->Body    = self::welcomeTemplate($firstName, $to, $password, $memberNum);
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer::sendWelcome — ' . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Génère le corps HTML de l'email de bienvenue.
     */
    private static function welcomeTemplate(string $firstName, string $to, string $password, string $memberNum): string {
        return '
        <p>Bonjour <strong>' . htmlspecialchars($firstName) . '</strong>,</p>
        <p>Votre compte sur <strong>' . APP_NAME . '</strong> vient d\'être créé.</p>
        <table cellpadding="6" style="border-collapse:collapse;">
            <tr><td><strong>Email</strong></td><td>' . htmlspecialchars($to) . '</td></tr>
            <tr><td><strong>Mot de passe</strong></td><td>' . htmlspecialchars($password) . '</td></tr>
            ' . (!empty($memberNum) ? '<tr><td><strong>N° adhérent</strong></td><td>' . htmlspecialchars($memberNum) . '</td></tr>' : '') . '
        </table>
        <p style="margin-top:16px;color:#888;font-size:0.9em;">Pensez à modifier votre mot de passe après votre première connexion.</p>
        ';
    }
}
