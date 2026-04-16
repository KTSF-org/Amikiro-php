<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use modele\Section;
use app\util\Error;
use PDO;

class SectionSpecimenDAO extends SectionDAO
{

    public function __construct()
    {
        base\Database::__construct('SectionSpecimen', 'id');
    }
}