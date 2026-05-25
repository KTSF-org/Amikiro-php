<?php

namespace modele;
use app\util\Error;
use modele\DAO\UserDAO;

/**
 * Modèle métier : compte utilisateur (table User).
 *
 * Encapsule toutes les données d'un utilisateur et délègue les opérations
 * de persistance à UserDAO (modele/DAO/UserDAO.php).
 *
 * Règles importantes :
 *   - $id (clé primaire) n'est PAS dans le constructeur : il est auto-incrémenté
 *     par MySQL et injecté via setId() après l'INSERT.
 *   - Les noms des propriétés du constructeur doivent correspondre exactement
 *     aux noms de colonnes de la table SQL User.
 *   - $memberNum est nullable (chaîne vide pour les invités) et exclu de la
 *     validation Error::checkModelArgs() pour cette raison.
 *   - setPassword() hache automatiquement le mot de passe via bcrypt (cost 12).
 *     Ne jamais stocker ni transmettre le mot de passe en clair après cet appel.
 *   - verifIdentifiant() est la méthode d'authentification principale : elle
 *     retourne l'objet User complet en cas de succès, false sinon.
 */
class User
{

	private int $id = 0; // Clé primaire — jamais dans le constructeur (auto-incrémentée)
	protected $param = []; // Liste des noms d'attributs pour UserDAO::getAllData()

	// Les noms des propriétés ci-dessous doivent correspondre aux noms de colonnes SQL.
	// Ne pas inclure la clé primaire ($id) — elle est gérée séparément.
	public function __construct(
		private int $codeRole = -1,
		private string $mail = '',
		private int $uptime = -1,
		private string $password = '',
		private string $name = '',
		private string $surname = '',
		private int $countConnect = -1,
		private string $memberNum = ''
	) {

		// Gestionnaire d'erreur (pour les requêtes) :
		try {
			$this->param = $this->getKey(get_object_vars($this));
			// memberNum est optionnel pour les comptes invités (null en BDD)
			$checkVars = get_object_vars($this);
			unset($checkVars['memberNum']);
			Error::checkModelArgs($checkVars, __CLASS__, func_get_args());
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


	/** Retourne true si l'email est au format valide (ne vérifie pas son existence). */
	public function isValidEmail(): bool
	{
		return filter_var($this->mail, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Authentifie un utilisateur par email + mot de passe.
	 * Retourne l'objet stdClass de la ligne BDD si succès, false sinon.
	 * Appelé dans controleur/common/Login.php et controleur/admin/AdminControleur.php.
	 */
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


	/**
	 * Hache le mot de passe via bcrypt (cost 12) avant de le stocker.
	 * Ne jamais appeler getPassword() pour comparer en clair — utiliser password_verify().
	 */
	public function setPassword($password)
	{
		$option = ['cost' => 12];
		$this->password = password_hash($password, PASSWORD_DEFAULT, $option);

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
