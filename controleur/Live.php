<?php

namespace controleur;

use modele\DAO\ConfigDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Live
{
    public function __construct()
    {
        Guard::requireLogin();
        $this->handle();
    }

    private function handle(): void
    {
        $config = new ConfigDAO();
        $url1   = $config->getURLbyId(1);

        $viewerLimit     = (int)($url1['viewerLimit'] ?? 0);
        $viewerCount     = (int)($url1['viewerCount'] ?? 0);
        $sessionDuration = (int)($url1['sessionDuration'] ?? 0);

        // Vérifie si la durée de session est expirée avant de re-admettre l'utilisateur.
        // live_started_at est posé à la première registration et jamais réinitialisé.
        if ($sessionDuration > 0 && isset($_SESSION['live_started_at'])) {
            $elapsed = time() - (int)$_SESSION['live_started_at'];
            if ($elapsed >= $sessionDuration) {
                Vue::setTitle('Live');
                Vue::render('live/LiveExpire');
                return;
            }
        }

        // Si la limite est activée (> 0) et atteinte, affiche la page d'attente sans incrémenter
        if ($viewerLimit > 0 && $viewerCount >= $viewerLimit) {
            Vue::setTitle('Live');
            Vue::render('live/LiveLimite');
            return;
        }

        $this->registerViewer($config, $url1, $viewerCount);

        // Temps restant calculé depuis le timestamp de début de session.
        // Permet de ne pas réinitialiser le timer à la pleine durée sur actualisation.
        $remaining = $sessionDuration;
        if ($sessionDuration > 0 && isset($_SESSION['live_started_at'])) {
            $remaining = $sessionDuration - (time() - (int)$_SESSION['live_started_at']);
            $remaining = max(1, $remaining);
        }

        Vue::setTitle('Live');
        Vue::addCSS([ASSET . '/css/live.css']);
        Vue::addJS([ASSET . '/js/live.js']);
        Vue::render('live/Live', ['url1' => $url1, 'remaining' => $remaining]);
    }

    /**
     * Enregistre le viewer courant si ce n'est pas déjà fait pour cette session.
     * Pose live_started_at une seule fois — jamais réinitialisé, sert à calculer le temps restant.
     */
    private function registerViewer(ConfigDAO $config, array &$url1, int $viewerCount): void
    {
        if (isset($_SESSION['in_live'])) {
            return;
        }

        $config->incrementViewers();
        $_SESSION['in_live'] = true;

        // Ne pas écraser live_started_at s'il existe déjà.
        // beforeunload (déclenché au refresh) détruit in_live via liveLeave,
        // mais live_started_at doit survivre pour que le décompte continue
        // depuis le début réel de la session, pas depuis le dernier refresh.
        if (!isset($_SESSION['live_started_at'])) {
            $_SESSION['live_started_at'] = time();
        }

        $url1['viewerCount'] = $viewerCount + 1;
    }
}
