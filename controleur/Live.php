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

        // Récupère la ligne de config (id=1) : URL du stream, limite et compteur de viewers
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

        if (!isset($_SESSION['in_live'])) {
            $config->incrementViewers();
            $_SESSION['in_live'] = true;
        }
        $url1 = $config->getURLbyId(1);

        Vue::setTitle('Live');
        Vue::render('live/Live', ['url1' => $url1]);

    }
}
