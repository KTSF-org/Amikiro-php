<?php

namespace controleur\journal;

use modele\DAO\journalDAO\CategoryDAO;
use modele\DAO\journalDAO\SectionColonyDAO;
use modele\DAO\journalDAO\SectionSpecimenDAO;
use vue\base\MainTemplate as Vue;
use modele\DAO\journalDAO\SectionDAO;
use modele\DAO\journalDAO\BatDAO;
use modele\journal\Section;
use app\util\Request as req;
use app\util\BaseURL as url;

class SectionRead
{
	public function __construct()
	{
		$urlRetour = url::getBaseUrl() . '/journal';
		$idFiche = req::get("id");
		$sectionDAO = new SectionDAO();
		$fiche = $sectionDAO->find($idFiche);

		$sectionColonyDAO = new SectionColonyDAO();
		$sectionSpecimenDAO = new SectionSpecimenDAO();

		$sectionTitle = $fiche->getTitle();
		$sectionContent = $fiche->getContent();
		$creationDate = $fiche->getModifDate();

		$nameCategory = "";
		$nameBat = "";

		$ficheColony = $sectionColonyDAO->findColonySectionByIdSection($idFiche);
		$ficheBat = $sectionSpecimenDAO->findSpecimenSectionByIdSection($idFiche);
		if($ficheColony != null){
			$categoryDAO = new CategoryDAO();
			$idCategory = $ficheColony->getIdCategory();
			$category = $categoryDAO->findById($idCategory);
			$nameCategory = $category->getName();
		}else{
			$batDAO = new BatDAO();
			$idBat = $ficheBat->getIdBat();
			$bat = $batDAO->getBatById($idBat);
			$nameBat = $bat->getName();
		}









		Vue::render(
			'journal/SectionRead',
			[
				"sectionTitle" => $sectionTitle,
				"sectionContent" => $sectionContent,
				"creationDate" => $creationDate,
				"nameCategory" => $nameCategory,
				"nameBat" => $nameBat,
				"urlRetour" => $urlRetour,
			]
		);
	}
}





?>
