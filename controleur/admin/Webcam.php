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
class Webcam
{
    public function __construct()
    {
        Guard::requireRole(ROLE_ADMIN);

        $configDAO = new ConfigDAO();
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $streamUrl = Request::post('streamUrl');
            $sessionDuration = (int) ($_POST['sessionDuration'] ?? 3600);
            $viewerLimit = (int) ($_POST['viewerLimit'] ?? 10);

            $durationInvalid = $sessionDuration !== 0 && $sessionDuration < 60;
            $limitInvalid = $viewerLimit < 0;

            if ($durationInvalid || $limitInvalid) {
                $error = 'Valeurs invalides (durée : 0 ou ≥ 60 s ; viewers : 0 ou plus).';
            } else {
                //On teste si le flux est en ligne
                $isOnline = !empty($streamUrl) && $this->isStreamOnline($streamUrl);

                //On sauvegarde TOUT, y compris le statut du flux
                $success = $configDAO->updateConfig([
                    'streamUrl' => $streamUrl,
                    'sessionDuration' => $sessionDuration,
                    'viewerLimit' => $viewerLimit,
                    'streamOnline' => $isOnline ? 1 : 0 // On stocke 1 (OK) ou 0 (HS)
                ]);

                if (!$success) {
                    $error = 'Erreur lors de la sauvegarde.';
                } elseif (!$isOnline && !empty($streamUrl)) {
                    // La sauvegarde a marché, mais on prévient que le flux est configuré comme HS
                    $error = "Configuration enregistrée, mais le flux vidéo semble actuellement HS ou injoignable.";
                    $success = null;
                }
            }
        }
        // Chargement de la configuration courante (Objet stdClass)
            $config = $configDAO->getConfig();

            Vue::setTitle('Configuration Webcam');
            Vue::render('admin/Webcam', [
                'config' => $config,
                'success' => $success,
                'error' => $error,
            ]);
    }
    private function isStreamOnline(string $url): bool
    {
        $parts = parse_url($url);
        if (!$parts || !isset($parts['scheme']))
            return false;

        $scheme = strtolower($parts['scheme']);
        $host = $parts['host'] ?? '';

        if ($scheme === 'http' || $scheme === 'https') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            return ($httpCode >= 200 && $httpCode < 400);
        }

        if ($scheme === 'rtsp' || $scheme === 'rtmp') {
            $port = $parts['port'] ?? (($scheme === 'rtsp') ? 554 : 1935);
            if (empty($host))
                return false;
            $connection = @fsockopen($host, $port, $errno, $errstr, 3);
            if (is_resource($connection)) {
                fclose($connection);
                return true;
            }
        }
        return false;
    }
}