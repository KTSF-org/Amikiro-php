<?php 

namespace app\util;

use modele\DAO\UserDAO;
use app\util\SessionLogin as UserSession;

// Classe a appelé si besoin des infos du user : nom, prenom etc
// a mettre dans le controller
// exemple : $user = UserInfo::getUserInfo();

class UserInfo {

    public static function getUserInfo(): mixed {
        $userId = UserSession::getUserId();
		$userDAO = new UserDAO();
		$user = $userDAO->getUsersById($userId);
        

        return $user;
    }


}










?>