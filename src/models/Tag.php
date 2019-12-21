<?php

namespace Models;

/**
 * CLASSE GERANT LES TAGS
 */
class Tag extends Model
{
    /**
     * RECUPERER TOUTES LES ETIQUETTES
     */
    public function getAllTags() {
        $req = $this->db->prepare('SELECT * FROM tag');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
