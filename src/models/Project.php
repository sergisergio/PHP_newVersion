<?php

namespace Models;

/**
 * CLASSE GERANT LES PROJETS
 */
class Project extends Model
{
    /**
     * RECUPERER TOUS LES PROJETS PUBLIES
     */
    public function getAllPublishedProjects() {
        $req = $this->db->prepare('
            SELECT p.id, p.title, p.description, p.link, p.created_at, p.published, i.url as url, i.alt as alt, i.style as style, c.name as category
            FROM projects p
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN projects_category r on p.id = r.projects_id
            INNER JOIN category c on r.category_id = c.id
            WHERE p.published = 1');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUS LES PROJETS
     */
    public function getAllProjects() {
        $req = $this->db->prepare('
            SELECT p.id, p.title, p.description, p.link, p.created_at, p.published, i.url as url, i.alt as alt, i.style as style, c.name as category
            FROM projects p
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN projects_category r on p.id = r.projects_id
            INNER JOIN category c on r.category_id = c.id');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE DE PROJETS
     */
    public function getNumberOfProjects() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM projects');
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * @param $this_page_first_result
     * @param $results_per_page
     * @return array
     *
     * RECUPERER DES PROJETS SELON LA PAGINATION
     */
    public function getProjectsPagination($start, $results_per_page) {
        $req = $this->db->prepare('
            SELECT p.id, p.title, p.description, p.link, p.created_at, p.published, i.url as url, i.alt as alt, i.style as style, c.name as category
            FROM projects p
            LEFT JOIN image i on p.img_id = i.id
            LEFT JOIN projects_category r on p.id = r.projects_id
            LEFT JOIN category c on r.category_id = c.id
            ORDER BY p.title
            LIMIT :start, :results_per_page');
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
