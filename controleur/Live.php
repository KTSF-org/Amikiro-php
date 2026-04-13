<?php

namespace controleur;


use modele\DAO\VideoDAO;
use modele\User;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Live {

    public function __construct() {
        Guard::requireLogin();

        $video = new VideoDAO();

        $url1= $video->getURLbyId(1);

        

        Vue::setTitle('Live');

        Vue::render('Live', [ 'url1'=>$url1
                            ]);

    }
}
