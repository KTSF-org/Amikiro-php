<?php

namespace controleur\admin;

use app\util\Guard;
use app\util\Request;
use modele\DAO\ConfigDAO;
use vue\base\MainTemplate as Vue;

/**
 * CONTRÔLEUR : Admin / Configuration mail
 *
 * Affiche et sauvegarde les paramètres SMTP stockés dans la table Config (id=1).
 * Accès restreint à ROLE_ADMIN.
 *
 * Paramètres gérés :
 *   - mailHost     : serveur SMTP (ex: smtp-relay.brevo.com)
 *   - mailPort     : port SMTP (587 STARTTLS / 465 SMTPS)
 *   - mailUser     : identifiant SMTP
 *   - mailPass     : mot de passe / clé API SMTP
 *   - mailFrom     : adresse expéditeur
 *   - mailFromName : nom affiché dans l'expéditeur
 */
class MailConfig
{
    public function __construct()
    {
        Guard::requireRole(ROLE_ADMIN);

        $configDAO = new ConfigDAO();
        $success   = null;
        $error     = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host     = Request::post('mailHost');
            $port     = (int) ($_POST['mailPort'] ?? 587);
            $user     = Request::post('mailUser');
            $pass     = Request::post('mailPass');
            $from     = Request::post('mailFrom');
            $fromName = Request::post('mailFromName');

            if (empty($host) || empty($from)) {
                $error = 'Le serveur SMTP et l\'adresse expéditeur sont obligatoires.';
            } elseif (!in_array($port, [25, 465, 587, 2525], true)) {
                $error = 'Port invalide. Valeurs acceptées : 25, 465, 587, 2525.';
            } else {
                $updated = $configDAO->updateConfig([
                    'mailHost'     => $host,
                    'mailPort'     => $port,
                    'mailUser'     => $user,
                    // Ne pas écraser le mot de passe si le champ est laissé vide
                    'mailFrom'     => $from,
                    'mailFromName' => $fromName ?: 'Amikiro',
                ]);

                // Le mot de passe est mis à jour séparément seulement s'il n'est pas vide
                if (!empty($pass)) {
                    $configDAO->updateConfig(['mailPass' => $pass]);
                }

                $success = $updated;
                if (!$updated) $error = 'Erreur lors de la sauvegarde.';
            }
        }

        $config = $configDAO->getConfig();

        Vue::setTitle('Configuration mail');
        Vue::render('admin/MailConfig', [
            'config'  => $config,
            'success' => $success,
            'error'   => $error,
        ]);
    }
}
