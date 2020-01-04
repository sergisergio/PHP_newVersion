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
}
