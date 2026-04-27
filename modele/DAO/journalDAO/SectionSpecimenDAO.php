<?php

namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use modele\journal\Section;
use app\util\Error;
use PDO;

class SectionSpecimenDAO extends SectionDAO
{

    public function __construct()
    {
        base\Database::__construct('SectionSpecimen', 'id');
    }
}