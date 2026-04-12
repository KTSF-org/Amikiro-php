<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\Request;
use modele\DAO\ConfigDAO;

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

        $config = $configDAO->getConfig();

        Vue::setTitle('Configuration Webcam');
        Vue::render('admin/Webcam', [
            'config'  => $config,
            'success' => $success,
            'error'   => $error,
        ]);
    }
}
