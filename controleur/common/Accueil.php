<?php

namespace controleur\common;

use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use app\util\SessionLogin as UserSession;
use app\util\Guard;

class Accueil {

    public function __construct() {
        Guard::requireLogin();
        $this->handle();
    }

    private function handle(): void {
        // La session ne stocke que l'ID et le rôle — un appel BDD est nécessaire
        // pour récupérer le prénom et le nom affichés sur la page d'accueil.
        $user = (new UserDAO())->getUsersById(UserSession::getUserId());

        Vue::setTitle('Accueil');
        Vue::addCSS([ASSET . '/css/accueil.css']);
        Vue::render('common/Accueil', [
            'surname' => $user->getSurname(),
            'name'    => $user->getName(),
        ]);
    }
}
