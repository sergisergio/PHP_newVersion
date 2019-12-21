<?php

namespace Models;

use Config\Db;

/**
 *  CLASSE MERE / CONNEXION A LA BDD
 */
class Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new Db;
    }
}
