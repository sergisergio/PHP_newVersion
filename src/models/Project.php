<?php

namespace Models;

/**
 * CLASSE GERANT LES PROJETS
 */
class Project extends Model
{
    public function getLastId() {
        return $this->db->lastInsertId();
    }
    /**
     * @param $data
     * @return bool
     *
     * CREER UN PROJET
     */
    public function setProject($data) {
        $req = $this->db->prepare('
            INSERT INTO projects (title, description, link, created_at, img_id, published)
            VALUES (:title, :description, :link, NOW(), :img_id, :published)');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':description', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':link', $data['link'], \PDO::PARAM_STR);
        $req->bindValue(':img_id', $data['img_id'], \PDO::PARAM_INT);
        $req->bindValue(':published', $data['published'], \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * METTRE A JOUR UN PROJET
     */
    public function updateProject($data) {
        $req = $this->db->prepare('
            UPDATE projects
            SET title = :title, description = :description, link = :link, img_id = :img_id, published = :published
            WHERE id = :id');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':description', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':link', $data['link'], \PDO::PARAM_STR);
        $req->bindValue(':img_id', $data['img_id'], \PDO::PARAM_INT);
        $req->bindValue(':published', $data['published'], \PDO::PARAM_INT);
        $req->bindValue(':id', $data['id'], \PDO::PARAM_INT);
        return $req->execute();
    }
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

    /**
     * DEPUBLIER UN PROJET
     */
    public function unPublish($id) {
        $req = $this->db->prepare('
            UPDATE projects
            SET published = 0
            WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();

    }

    /**
     * PUBLIER UN PROJET
     */
    public function publish($id) {
        $req = $this->db->prepare('
            UPDATE projects
            SET published = 1
            WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();

    }

    /**
     * @param $id
     * @return mixed
     *
     * RECUPERE UN PROJET AVEC SON ID
     */
    public function getProjectById($id) {
        $req = $this->db->prepare('
            SELECT *
            FROM projects p
            WHERE p.id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UN PROJET
     */
    public function deleteProject(int $id) {
        $req = $this->db->prepare('
            DELETE FROM projects
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
