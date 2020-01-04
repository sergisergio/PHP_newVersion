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
            FROM category c');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getNumberOfPosts($category) {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM (
                SELECT c.id, c.name, p.title
                FROM category c
                LEFT JOIN category_posts x ON c.id = x.category_id
                LEFT JOIN posts p ON p.id = x.posts_id
                WHERE c.name = :category
            ) as get_posts');
        $req->bindParam(':category', $category, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchColumn();
    }

    /**
     * @param $data
     * @return bool
     *
     * CREER UNE CATEGORIE
     */
    public function setCategory($data) {
        $req = $this->db->prepare('
            INSERT INTO category (name, numberPosts)
            VALUES (:name, :numberPosts)');
        $req->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $req->bindValue(':numberPosts', $data['numberPosts'], \PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UNE CATEGORIE
     */
    public function deletecategory(int $id) {
        $req = $this->db->prepare('
            DELETE
            FROM category
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * @param $category
     * @return bool
     *
     * AJOUTER UNE CATEGORIE A UN ARTICLE
     */
    public function addCategoryToPost($category, $last_id) {
        $req = $this->db->prepare('
            INSERT INTO category_posts (category_id, posts_id)
            VALUES (:category_id, :posts_id)');
        $req->bindValue(':category_id', $category, \PDO::PARAM_INT);
        $req->bindValue(':posts_id', $last_id, \PDO::PARAM_INT);

        return $req->execute();
    }
    /**
     * INCREMENTE LE NOMBRE D'ARTICLES AVEC CETTE CATEGORIE
     */
    public function plusNumberPosts($category) {
        $req = $this->db->prepare('
            UPDATE category
            SET numberPosts = numberPosts + 1
            WHERE id = :id');
        $req->bindParam(':id', $category, \PDO::PARAM_INT);
        return $req->execute();

    }
    /**
     * DECREMENTE LE NOMBRE D'ARTICLES AVEC CETTE CATEGORIE
     */
    public function minusNumberPosts($category) {
        $req = $this->db->prepare('
            UPDATE category
            SET numberPosts = numberPosts - 1
            WHERE id = :id');
        $req->bindParam(':id', $category, \PDO::PARAM_INT);
        return $req->execute();

    }

    public function deleteFromCategoryPosts($category) {
        $req = $this->db->prepare('
            DELETE
            FROM category_posts
            WHERE category_id = :category_id
            LIMIT 1');
        $req->bindParam(':category_id', $category, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * METTRE A JOUR UNE CATEGORIE
     */
    public function updateCategory($title, $id) {
        $req = $this->db->prepare('
            UPDATE category
            SET name = :name
            WHERE id = :id');
        $req->bindParam(':name', $title, \PDO::PARAM_STR);
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
