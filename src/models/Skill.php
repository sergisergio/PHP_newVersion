<?php

namespace Models;

/**
 * CLASSE GERANT LES COMPETENCES
 */
class Skill extends Model
{
    /**
     * RECUPERER TOUTES LES COMPETENCES ((PROGRESS BAR / PARTIE GAUCHE))
     */
    public function getAllSkills() {
        $req = $this->db->prepare('SELECT * FROM skill');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUTES LES COMPETENCES ((PARTIE DROITE))
     */
    public function getAllSkills2() {
        $req = $this->db->prepare('SELECT * FROM skill2');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
