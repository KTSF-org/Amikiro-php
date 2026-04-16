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

        $config = new ConfigDAO();
        $url1   = $config->getURLbyId(1);

        $viewerLimit = (int)($url1['viewerLimit'] ?? 0);
        $viewerCount = (int)($url1['viewerCount'] ?? 0);

        if ($viewerLimit > 0 && $viewerCount >= $viewerLimit) {
            Vue::setTitle('Live');
            Vue::render('LiveLimite');
            return;
        }

        $config->incrementViewers();

        Vue::setTitle('Live');
        Vue::render('Live', ['url1' => $url1]);

    }
}
