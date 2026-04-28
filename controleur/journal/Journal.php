<?php

namespace controleur\journal;

use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use modele\DAO\journalDAO\SectionDAO;


class Journal {

    public function __construct() {
        Guard::requireRole(ROLE_ADHERENT);

        $sectionDAO = new SectionDAO();
        $listFiches = $sectionDAO->findAll();

        $userDAO = new UserDAO();
        $users = $userDAO->findAll();
        
        $usersAsso = [];
        foreach($users as $u){
            $usersAsso[$u->getId()] = $u->getSurname() . " " . $u->getName();
        }
        
        


        Vue::setTitle('Journal');
        Vue::render(
            'journal/Journal',
            [
                'listFiches' => $listFiches,
                'usersAsso' => $usersAsso
            ]
        );
        
    }
}