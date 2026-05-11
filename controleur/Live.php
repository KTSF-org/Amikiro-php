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

        // Cast en int pour éviter une comparaison incorrecte si la valeur est null en base
        $viewerLimit = (int)($url1['viewerLimit'] ?? 0);
        $viewerCount = (int)($url1['viewerCount'] ?? 0);

        // Si la limite est activée (> 0) et atteinte, affiche la page d'attente sans incrémenter
        if ($viewerLimit > 0 && $viewerCount >= $viewerLimit) {
            Vue::setTitle('Live');
            Vue::render('LiveLimite');
            return;
        }

        $this->registerViewer($config, $url1, $viewerCount);

        Vue::setTitle('Live');
        Vue::addCSS([ASSET . '/css/live.css']);
        Vue::addJS([ASSET . '/js/live.js']);
        Vue::render('live/Live', ['url1' => $url1]);
    }

    /**
     * Enregistre le viewer courant si ce n'est pas déjà fait pour cette session.
     * Met à jour $url1 localement après l'incrément pour éviter un second appel BDD :
     * la vue a besoin du compteur post-incrément, pas d'un refetch.
     */
    private function registerViewer(ConfigDAO $config, array &$url1, int $viewerCount): void
    {
        if (isset($_SESSION['in_live'])) {
            return;
        }

        $config->incrementViewers();
        $_SESSION['in_live'] = true;

        // Reflète l'incrément dans le tableau sans retourner en base
        $url1['viewerCount'] = $viewerCount + 1;
    }
}
