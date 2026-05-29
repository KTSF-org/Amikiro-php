<?php

namespace app\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use modele\DAO\ConfigDAO;

/**
 * Utilitaire d'envoi d'emails via SMTP (PHPMailer).
 *
 * La configuration SMTP (hôte, port, identifiants) est lue depuis la table Config (id=1)
 * via ConfigDAO. Les colonnes mailHost, mailPort, mailUser, mailPass, mailFrom, mailFromName
 * sont ajoutées par migration_mail_config.sql.
 * Si mailUser est vide, l'authentification SMTP est désactivée (usage local/Docker).
 */
class Mailer
{

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
    public static function sendWelcome(string $to, string $firstName, string $password, string $memberNum): bool
    {
        $cfg  = (new ConfigDAO())->getConfig();
        $host = $cfg->mailHost     ?? '';
        $port = (int)($cfg->mailPort    ?? 587);
        $user = $cfg->mailUser     ?? '';
        $pass = $cfg->mailPass     ?? '';
        $from = $cfg->mailFrom     ?? '';
        $name = $cfg->mailFromName ?? APP_NAME;

        if (empty($host) || empty($from)) {
            error_log('Mailer::sendWelcome — configuration SMTP incomplète (mailHost ou mailFrom manquant).');
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;

            // Port 465 → SMTPS (SSL direct) | tout autre port → STARTTLS
            $mail->SMTPSecure = ($port === 465)
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;

            // Auth désactivée si pas de credentials (Mailpit local, Postfix sans auth)
            if (!empty($user)) {
                $mail->SMTPAuth = true;
                $mail->Username = $user;
                $mail->Password = $pass;
            } else {
                $mail->SMTPAuth = false;
            }

            $mail->CharSet = 'UTF-8';
            $mail->setFrom($from, $name);
            $mail->addAddress($to, $firstName);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue...';
            $mail->Body = self::welcomeMail($firstName, $to, $password, $memberNum);

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
    private static function welcomeMail(string $firstName, string $mail, string $password, string $memberNum): string
    {
        return '
        <p>Bonjour <strong>' . htmlspecialchars($firstName) . '</strong>,</p>
        <p>Votre compte sur <strong>' . APP_NAME . '</strong> vient d\'être créé.</p>
        <table cellpadding="6" style="border-collapse:collapse;">
            <tr><td><strong>Email</strong></td><td>' . htmlspecialchars($mail) . '</td></tr>
            <tr><td><strong>Mot de passe</strong></td><td>' . htmlspecialchars($password) . '</td></tr>
            ' . (!empty($memberNum) ? '<tr><td><strong>N° adhérent</strong></td><td>' . htmlspecialchars($memberNum) . '</td></tr>' : '') . '
        </table>
        <p style="margin-top:16px;color:#888;font-size:0.9em;">Pensez à modifier votre mot de passe après votre première connexion.</p>
        ';
    }
}
