<?php

namespace Models;

/**
 * CLASSE GERANT LES COMPETENCES
 */
class Link extends Model
{
    public function getAllLinks() {
        $req = $this->db->prepare('
            SELECT *
            FROM links');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllSublinks() {
        $req = $this->db->prepare('
            SELECT l.id, l.name, l.active, l.class, s.name as sublinkname, s.url as sublinkurl, s.link_id as link_id
            FROM links l
            INNER JOIN sublink s ON l.id = s.link_id');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
