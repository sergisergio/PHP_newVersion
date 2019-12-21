<?php

namespace Models;

/**
 * CLASSE GERANT LES COMMENTAIRES
 */
class Comment extends Model
{
    /**
     * RECUPERER TOUS LES COMMENTAIRES
     */
    public function getAllComments() {
        $req = $this->db->prepare('SELECT * FROM comment');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUS LES COMMENTAIRES VALIDES
     */
    public function getVerifiedCommentsByPostId($id) {
        $req = $this->db->prepare('
            SELECT c.id, c.content, c.post_id, c.published_at, c.validated, u.username as author, i.url as image
            FROM comment c
            INNER JOIN user u ON c.author_id = u.id
            INNER JOIN image i ON u.avatar_id = i.id
            WHERE validated = 1 AND post_id = :post_id');
        $req->bindValue(':post_id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     *  AJOUTER UN COMMENTAIRE
     */
    public function addComment($data) {
        $req = $this->db->prepare('
            INSERT INTO comment (post_id, content, author_id, published_at, validated)
            VALUES (:post_id, :content, :user_id, NOW(), :validated)');
        $req->bindValue(':post_id', $data['post_id'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $req->bindValue(':validated', $data['validated'], \PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * RECUPERER UN COMMENTAIRE AVEC SON ID
     */
    public function getCommentById($id, $postId) {
        $req = $this->db->prepare('
            SELECT *
            FROM comment
            WHERE id = :id
            AND post_id = :post_id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->bindValue(':post_id', $postId, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * SUPPRIMER UN COMMENTAIRE
     */
    public function deleteComment($commentId) {
        $req = $this->db->prepare('DELETE FROM comment WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $commentId, \PDO::PARAM_INT);
        return $req->execute();
    }
}
