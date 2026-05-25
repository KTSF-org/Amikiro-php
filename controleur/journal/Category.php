<?php

namespace controleur\journal;

use vue\base\MainTemplate as Vue;
use modele\DAO\journalDAO\CategoryDAO;
use app\util\Guard;

/**
 * Contrôleur : gestion des catégories d'observation du journal.
 *
 * Une catégorie est un type d'événement associé aux fiches colonie
 * (ex : "Départ chasse", "Pause", "Rentrée gîte"…).
 * La création et la suppression des catégories sont réservées aux naturalistes
 * et sont gérées via AJAX depuis la vue (MainAjax.php).
 *
 * Ce contrôleur se limite à charger la liste des catégories existantes
 * et à la passer à la vue pour l'affichage et la gestion.
 */
class Category {

	public function __construct() {
		Guard::requireRole(ROLE_NATURALISTE);

		// Charge toutes les catégories disponibles pour les afficher dans la vue
		$cat = new CategoryDAO();
		$allCategories = $cat->getAllCategories();

		Vue::setTitle('Catégories');
		Vue::render(
			'journal/Category',
			[
				'categories' => $allCategories,
			]
		);
	}
}
