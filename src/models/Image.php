<?php

namespace Models;

/**
 * CLASSE GERANT LES IMAGES
 */
class Image extends Model
{
    /**
     * AJOUTER UNE IMAGE
     */
    public function setImage($image) {
        $req = $this->db->prepare('
            INSERT INTO image (url)
            VALUES (:url)');
        $req->bindValue(':url', $image, \PDO::PARAM_STR);
        return $req->execute();
    }
    /**
     * RECUPERER L'ID D'UNE IMAGE
     */
    public function getId($image) {
        $req = $this->db->prepare('
            SELECT id
            FROM image
            WHERE url = :url');
        $req->bindValue(':url', $image, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UNE IMAGE
     */
    public function deleteImage($imageId) {
        $req = $this->db->prepare('
            DELETE FROM image
            WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $imageId, \PDO::PARAM_INT);
        return $req->execute();
    }
}
