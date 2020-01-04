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
        $req = $this->db->prepare('
            SELECT *
            FROM comment');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUS LES COMMENTAIRES VALIDES
     */
    public function getVerifiedCommentsByPostId($id) {
        $req = $this->db->prepare('
            SELECT c.id, c.content, c.post_id, c.published_at, c.validated, c.likes, c.dislikes, u.username as author, i.url as image
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
    public function getCommentById($id) {
        $req = $this->db->prepare('
            SELECT c.id, c.content, c.post_id, c.published_at, c.validated, s.id as sub_id, s.content as sub_content, s.published_at as sub_published_at, s.validated as sub_validated, s.post_id as sub_post_id
            FROM comment c
            INNER JOIN subcomment s ON c.id = s.comment_id
            WHERE c.id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * SUPPRIMER UN COMMENTAIRE
     */
    public function deleteComment($commentId) {
        $req = $this->db->prepare('
            DELETE
            FROM comment
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $commentId, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE DE COMMENTAIRES PAR ARTICLES
     */
    public function getNumberOfComments($postId) {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM comment c
            INNER JOIN posts p
            ON p.id = c.post_id
            WHERE p.id = :post_id');
        $req->bindParam(':post_id', $postId, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchColumn();
    }

    /**
     *  AJOUTER UN SOUS-COMMENTAIRE
     */
    public function addSubComment($data) {
        $req = $this->db->prepare('
            INSERT INTO subcomment (post_id, content, author_id, comment_id, published_at, validated)
            VALUES (:post_id, :content, :user_id, :comment_id, NOW(), :validated)');
        $req->bindValue(':post_id', $data['post_id'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $req->bindValue(':comment_id', $data['comment_id'], \PDO::PARAM_INT);
        $req->bindValue(':validated', $data['validated'], \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * RECUPERE LES SOUS-COMMENTAIRES EN LIEN AVEC UN COMMENTAIRE
     */
    public function getSubCommentsByCommentId($commentId) {
        $req = $this->db->prepare('
            SELECT s.id, s.content, s.post_id, s.published_at, s.validated, u.username as author, i.url as image
            FROM subcomment s
            INNER JOIN user u ON s.author_id = u.id
            INNER JOIN comment c ON s.comment_id = c.id
            INNER JOIN image i ON u.avatar_id = i.id
            WHERE s.comment_id = :comment_id');
        $req->bindValue(':comment_id', $commentId, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * INCREMENTE LE NOMBRE DE LIKES D'UN COMMENTAIRE
     */
    public function plusLikes($commentId) {
        $req = $this->db->prepare('UPDATE comment
            SET likes = likes + 1
            WHERE id = :id');
        $req->bindParam(':id', $commentId, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * DECREMENTE LE NOMBRE DE LIKES D'UN COMMENTAIRE
     */

    public function minusLikes($commentId) {
        $req = $this->db->prepare('UPDATE comment
            SET likes = likes - 1
            WHERE id = :id');
        $req->bindParam(':id', $commentId, \PDO::PARAM_INT);
        return $req->execute();
    }


    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE DE COMMENTAIRES
     */
    public function getNumberComments() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM comment');
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * RETOURNE LES TagS ET PAGINATION
     */
    public function getCommentPagination($start, $results_per_page) {
        $req = $this->db->prepare("
            SELECT *
            FROM comment
            ORDER BY published_at DESC
            LIMIT :start, :results_per_page");
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
