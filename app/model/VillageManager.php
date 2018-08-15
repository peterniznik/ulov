<?php

namespace App\Model;

use Nette;

class VillageManager
{
    use Nette\SmartObject;

    /**
     * @var Nette\Database\Context
     */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getVillages()
    {   
     return $this->database->fetchAll('SELECT id FROM village');
    } 
    
}