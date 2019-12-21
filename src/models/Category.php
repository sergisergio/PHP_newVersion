<?php

namespace Models;

/**
 * CLASSE GERANT LES CATEGORIES
 */
class Category extends Model
{
    /**
     * RECUPERER TOUTES LES CATEGORIES
     */
    public function getAllCategories() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM category');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
