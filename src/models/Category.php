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

    /**
     * @param $data
     * @return bool
     *
     * CREER UNE CATEGORIE
     */
    public function setCategory($data) {
        $req = $this->db->prepare('
            INSERT INTO category (name)
            VALUES (:name)');
        $req->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        return $req->execute();
    }

    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UNE CATEGORIE
     */
    public function deletecategory(int $id) {
        $req = $this->db->prepare('DELETE FROM category WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
