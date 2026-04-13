<?php

namespace controleur;


use modele\DAO\ConfigDAO;
use modele\DAO\VideoDAO;
use modele\User;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Live
{

    public function __construct()
    {
        Guard::requireLogin();

        $video = new VideoDAO();
        $config = new ConfigDAO();

        $url1 = $video->getURLbyId(1);
        $url2 = $config->getURLbyId(1);



        Vue::setTitle('Live');

        Vue::render('Live', [
            'url1' => $url1,
            'url2' => $url2
        ]);

    }
}
