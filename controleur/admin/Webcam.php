<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\Request;
use modele\DAO\ConfigDAO;

/**
 * CONTRÔLEUR : Admin / Webcam
 * Affiche et sauvegarde la configuration du flux webcam.
 * Accès restreint à ROLE_ADMIN.
 *
 * La configuration est stockée en table Config (ligne unique, id=1),
 * accessible via ConfigDAO.
 *
 * Sur POST : valide les valeurs saisies et met à jour la configuration.
 * Sur GET  : charge la configuration existante et l'expose à la vue.
 *
 * Paramètres configurables :
 *   - streamUrl       : URL du flux vidéo (RTSP, HLS, HTTP…).
 *   - sessionDuration : durée de session par défaut en secondes (minimum 60).
 *   - viewerLimit     : nombre maximum de viewers simultanés (minimum 1).
 */
class Webcam {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $configDAO = new ConfigDAO();
        $success   = null;
        $error     = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $streamUrl       = Request::post('streamUrl');
            $sessionDuration = (int)($_POST['sessionDuration'] ?? 3600);
            $viewerLimit     = (int)($_POST['viewerLimit'] ?? 10);

            if ($sessionDuration < 60 || $viewerLimit < 1) {
                $error = 'Valeurs invalides (durée min. 60 s, viewers min. 1).';
            } else {
                $success = $configDAO->updateConfig([
                    'streamUrl'       => $streamUrl,
                    'sessionDuration' => $sessionDuration,
                    'viewerLimit'     => $viewerLimit,
                ]);
                if (!$success) $error = 'Erreur lors de la sauvegarde.';
            }
        }

        // Chargement de la configuration courante après une éventuelle mise à jour.
        $config = $configDAO->getConfig();

        Vue::setTitle('Configuration Webcam');
        Vue::render('admin/Webcam', [
            'config'  => $config,
            'success' => $success,
            'error'   => $error,
        ]);
    }
}
