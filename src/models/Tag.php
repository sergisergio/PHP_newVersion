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
        $req = $this->db->prepare('
            SELECT *
            FROM tag');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return bool
     *
     * CREER UNE ETIQUETTE
     */
    public function setTag($data) {
        $req = $this->db->prepare('
            INSERT INTO tag (name, numberPosts)
            VALUES (:name, :numberPosts)');
        $req->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $req->bindValue(':numberPosts', $data['numberPosts'], \PDO::PARAM_STR);
        return $req->execute();
    }
    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UNE ETIQUETTE
     */
    public function deleteTag(int $id) {
        $req = $this->db->prepare('
            DELETE
            FROM tag
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    public function updateTag($title, $id) {
        $req = $this->db->prepare('
            UPDATE tag
            SET name = :name
            WHERE id = :id');
        $req->bindParam(':name', $title, \PDO::PARAM_STR);
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE DE TAGS
     */
    public function getNumberOfTags() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM tag');
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * RETOURNE LES TAGS ET PAGINATION
     */
    public function getTagPagination($start, $results_per_page) {
        $req = $this->db->prepare("
            SELECT *
            FROM tag
            ORDER BY name ASC
            LIMIT :start, :results_per_page");
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function linkTagsToPost($singleTag, $last_id) {
        $req = $this->db->prepare('
            INSERT INTO tag_posts (tag_id, posts_id)
            VALUES (:tag_id, :posts_id)');
        $req->bindValue(':tag_id', $singleTag, \PDO::PARAM_INT);
        $req->bindValue(':posts_id', $last_id, \PDO::PARAM_INT);
        return $req->execute();
    }
    public function updateTagsToPost($singleTag, $id) {
        $req = $this->db->prepare('
            UPDATE tag_posts
            SET tag_id = :tag_id
            WHERE posts_id = :id');
        $req->bindParam(':tag_id', $singleTag, \PDO::PARAM_INT);
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    public function deleteTagsToPost($id) {
        $req = $this->db->prepare('
            DELETE
            FROM tag_posts
            WHERE posts_id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
