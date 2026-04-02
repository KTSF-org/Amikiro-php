<?php

namespace modele;
use app\util\Error;
use modele\DAO\UserDAO;

/**
 * MODELE : Objet métier : Direct Object (DO) : User 
 * Encapsulation, manipulation et récupération des données issues du DAO :
 * -> modele/DAO/UserDAO.php (hérités de : modele/DAO/base/Database.php)
 * Accesseurs / mutateurs de la table : "clients".
 * Logique métier à implémenter, par exemple : 
 * Calculer l'âge à partir de la date de naissance dans une méthode getAge() ...
 */

class User
{

	private int $id = 0; //La clé primaire est identifiée par $id

	protected $param = []; //La liste des paramètres (ou attributs)

	// Les autres paramètres sont ci-dessous, dans le constructeur...

	// Constructeur : User
	// Les noms des propriétés/attributs/colonnes de la table (en BDD),
	// doivent être identiques dans la déclaration du constructeur (ci-dessous).
	// Ne doit pas être ajouté : la clé primaire, car auto-incrémentée !
	public function __construct(
		private int $codeRole = -1,
		private string $mail = '',
		private int $uptime = -1,
		private string $password = '',
		private string $name = '',
		private string $surname = '',
		private int $countConnect = -1,
		private int $memberNum = -1
	) {

		// Gestionnaire d'erreur (pour les requêtes) :
		try {
			$this->param = $this->getKey(get_object_vars($this));
			Error::checkModelArgs(get_object_vars($this), __CLASS__, func_get_args());
		} catch (\InvalidArgumentException $e) {
			$err = "<pre>Erreur : " . $e->getMessage();
			$err .= Error::print($e->getTrace(), 1);
			exit($err);
		}
	}

	/**
	 * METHODS
	 */

	// STOCKER LA LISTE DES ATTRIBUTS
	private function getKey(array $arr): array
	{
		foreach ($arr as $key => $value) {
			if ($key === "id" or $key === "param")
				continue;
			$param[] = $key;
		}
		return $param;
	}

	// SORTIR LA LISTE DES ATTRIBUTS
	public function getParam(): array
	{
		return $this->param;
	}

	// CREATE
	public function addUser(): bool
	{
		$userDAO = new UserDAO();
		return $userDAO->create($this);
	}

	
	// UPDATE
	public function updateUser(): bool
	{
		$userDAO = new UserDAO();
		return $userDAO->update($this);
	}

	// DELETE
	public function deleteUser(): bool
	{
		$userDAO = new UserDAO();
		return $userDAO->delete($this);
	}


	// Vérification de l'email
	public function isValidEmail(): bool
	{
		return filter_var($this->mail, FILTER_VALIDATE_EMAIL);
	}


	// Fonction pour vérifier les identifiants
	public static function verifIdentifiant(string $mail, string $password): mixed
	{
		$userDAO = new UserDAO();
		$user = $userDAO->getUserByEmail($mail);

		if (!$user) {
			return false; //Email inconnu
		}

		//Compare le mdp avec le mdp hashe en bdd
		if (!password_verify($password, $user->password)) {
			return false; //Mot de passe incorrect
		}

		return $user; //Email connu / Mot de passe connu
	}

	


	/**
	 * GETTERS (accesseurs)
	 */

	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Get the value of codeRole
	 */
	public function getCodeRole()
	{
		return $this->codeRole;
	}

	/**
	 * Get the value of Mail
	 */
	public function getMail()
	{
		return $this->mail;
	}

	/**
	 * Get the value of Uptime
	 */
	public function getUptime()
	{
		return $this->uptime;
	}

	/**
	 * Get the value of Password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Get the value of Name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the value of Surname
	 */
	public function getSurname()
	{
		return $this->surname;
	}

	/**
	 * Get the value of CountConnect
	 */
	public function getCountConnect()
	{
		return $this->countConnect;
	}

	/**
	 * Get the value of MemberId
	 */
	public function getMemberNum()
	{
		return $this->memberNum;
	}


	/**
	 * SETTERS (mutateurs)
	 */

	public function setId($id): void
	{
		$this->id = $id;
	}


	public function setCodeRole($codeRole)
	{
		$this->codeRole = $codeRole;

		return $this;
	}

	
	public function setMail($mail)
	{
		$this->mail = $mail;

		return $this;
	}

	
	public function setUptime($uptime)
	{
		$this->uptime = $uptime;

		return $this;
	}

	
	public function setPassword($password)
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);

		return $this;
	}

	
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	
	public function setSurname($surname)
	{
		$this->surname = $surname;

		return $this;
	}

	
	public function setCountConnect($countConnect)
	{
		$this->countConnect = $countConnect;

		return $this;
	}

	
	public function setMemberNum($memberNum)
	{
		$this->memberNum = $memberNum;

		return $this;
	}
}
