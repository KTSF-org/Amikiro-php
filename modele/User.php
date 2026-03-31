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
		private string $Mail = '',
		private int $Uptime = -1,
		private string $Password = '',
		private string $Name = '',
		private string $Surname = '',
		private int $CountConnect = -1,
		private int $MemberId = -1
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

	// Vérification de l'email
	public function isValidEmail(): bool
	{
		return filter_var($this->email, FILTER_VALIDATE_EMAIL);
	}


	// Fonction pour vérifier les identifiants
	public static function verifIdentifiant(string $email, string $pwd): mixed
	{
		$userDAO = new UserDAO();
		$user = $userDAO->getUserByEmail($email);

		if (!$user) {
			return false; //Email inconnu
		}

		//Compare le mdp avec le mdp hashe en bdd
		if (!password_verify($pwd, $user->pwd)) {
			return false; //Mot de passe incorrect
		}

		return $user; //Email connu / Mot de passe connu
	}

	public function getUserById($id): mixed
	{
		$userDAO = new UserDAO();
		$user = $userDAO->getUserById($id);
		return $user;
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
		return $this->Mail;
	}

	/**
	 * Get the value of Uptime
	 */
	public function getUptime()
	{
		return $this->Uptime;
	}

	/**
	 * Get the value of Password
	 */
	public function getPassword()
	{
		return $this->Password;
	}

	/**
	 * Get the value of Name
	 */
	public function getName()
	{
		return $this->Name;
	}

	/**
	 * Get the value of Surname
	 */
	public function getSurname()
	{
		return $this->Surname;
	}

	/**
	 * Get the value of CountConnect
	 */
	public function getCountConnect()
	{
		return $this->CountConnect;
	}

	/**
	 * Get the value of MemberId
	 */
	public function getMemberId()
	{
		return $this->MemberId;
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

	
	public function setMail($Mail)
	{
		$this->Mail = $Mail;

		return $this;
	}

	
	public function setUptime($Uptime)
	{
		$this->Uptime = $Uptime;

		return $this;
	}

	
	public function setPassword($Password)
	{
		$this->Password = password_hash($Password, PASSWORD_DEFAULT);

		return $this;
	}

	
	public function setName($Name)
	{
		$this->Name = $Name;

		return $this;
	}

	
	public function setSurname($Surname)
	{
		$this->Surname = $Surname;

		return $this;
	}

	
	public function setCountConnect($CountConnect)
	{
		$this->CountConnect = $CountConnect;

		return $this;
	}

	
	public function setMemberId($MemberId)
	{
		$this->MemberId = $MemberId;

		return $this;
	}
}
