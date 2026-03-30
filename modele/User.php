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

class User {

	private int $id=0; //La clé primaire est identifiée par $id
	
	protected $param=[]; //La liste des paramètres (ou attributs)
	
	// Les autres paramètres sont ci-dessous, dans le constructeur...
	
	// Constructeur : User
	// Les noms des propriétés/attributs/colonnes de la table (en BDD),
	// doivent être identiques dans la déclaration du constructeur (ci-dessous).
	// Ne doit pas être ajouté : la clé primaire, car auto-incrémentée !
	public function __construct( 
		private string $nom='',
		private string $prenom='',
		private string $email='',
		private string $dateNaissance='',
		private string $ville='',
		private string $enfants='',
		private string $tel='',
		private string $avatar='') {

		// Gestionnaire d'erreur (pour les requêtes) :
		try {
			$this->param = $this->getKey(get_object_vars($this));
			Error::checkModelArgs(get_object_vars($this), __CLASS__ , func_get_args());
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
	private function getKey(array $arr): array {
		foreach($arr as $key => $value) {
			if($key==="id" or $key==="param")continue;
			$param[] = $key; 
		}
		return $param;
	}
	
	// SORTIR LA LISTE DES ATTRIBUTS
	public function getParam(): array {
		return $this->param;
	}
	
	// CREATE
	public function addUser(): bool {
		$userDAO = new UserDAO();
		return $userDAO->create($this);
	}

	// Vérification de l'email
	public function isValidEmail(): bool {
		return filter_var($this->email, FILTER_VALIDATE_EMAIL);
	}
	

	// Fonction pour vérifier les identifiants
	public static function verifIdentifiant(string $email, string $pwd): mixed {
		$userDAO = new UserDAO();
		$user = $userDAO -> getUserByEmail($email);

		if (!$user) {
			return false; //Email inconnu
		}

		//Compare le mdp avec le mdp hashe en bdd
		if (!password_verify($pwd, $user->pwd)) {
			return false; //Mot de passe incorrect
		}

		return $user; //Email connu / Mot de passe connu
	}

	/**
	 * GETTERS (accesseurs)
	 */
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getNom(): string {
		return $this->nom;
	}
	
	public function getPrenom(): string	{
		return $this->prenom;
	}
	
	public function getEmail(): string {
		return $this->email;
	}
	
	public function getDateNaissance(): string {
		return $this->dateNaissance;
	}
	
	public function getVille(): string {
		return $this->ville;
	}
	
	public function getEnfants(): string {
		return $this->enfants;
	}
	
	public function getTel(): string {
		return $this->tel;
	}
	
	public function getAvatar(): string {
		return $this->avatar;
	}
	
	/**
	 * SETTERS (mutateurs)
	 */
	
	public function setId($id): void {
		$this->id = $id;
	}
	
	public function setNom($nom): void {
		$this->nom = $nom;
	}
	
	public function setPrenom($prenom): void {
		$this->prenom = $prenom;
	}
	
	public function setEmail($email): void {
		$this->email = $email;
	}
	
	public function setDateNaissance($ne_le): void {
		$this->ne_le = $ne_le;
	}
	
	public function setVille($ville): void {
		$this->ville = $ville;
	}
	
	public function setEnfants($enfants): void {
		$this->enfants = $enfants;
	}
	
	public function setTel($tel): void {
		$this->tel = $tel;
	}

	public function setAvatar($avatar): void {
		$this->avatar = $avatar;
	}

}
