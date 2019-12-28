<?php

namespace Models;

/**
 * CLASSE GERANT LES PROJETS
 */
class Project extends Model
{
    /**
     * RECUPERER TOUS LES PROJETS
     */
    public function getAllProjects() {
        $req = $this->db->prepare('
            SELECT *
            FROM projects p
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN projects_category r on p.id = r.projects_id
            INNER JOIN category c on r.category_id = c.id');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
