<?php

namespace Models;

/**
 * CLASSE GERANT LES ARTICLES
 */
class Blog extends Model
{
    /**
     * @param $data
     * @return bool
     *
     * CREER UN ARTICLE
     */
    public function setPost($data) {
        $req = $this->db->prepare('
            INSERT INTO posts (title, content, created_at, user_id, published, numberComments)
            VALUES (:title, :content, NOW(), :user_id, :published, :numberComments)');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $req->bindValue(':published', $data['published'], \PDO::PARAM_INT);
        $req->bindValue(':numberComments', $data['numberComments'], \PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * @param $id
     * @return mixed
     *
     * RECUPERE UN ARTICLE AVEC SON ID
     */
    public function getPostById($id) {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image
                          FROM posts p
                          INNER JOIN user u on p.user_id = u.id
                          INNER JOIN image i on p.img_id = i.id
                          WHERE p.id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @param $postId
     * @return bool
     *
     * METTRE A JOUR UN ARTICLE AVEC SON ID
     */
    public function updatePost($data, $postId) {
        $req = $this->db->prepare('UPDATE posts SET title = :title, subtitle = :subtitle, content = :content, image = :image, active = :active, date_update = NOW() WHERE id = :id LIMIT 1');
        $req->bindValue(':id', $postId, \PDO::PARAM_INT);
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':subtitle', $data['subtitle'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_BOOL);
        return $req->execute();
    }

    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE D'ARTICLES
     */
    public function getNumberOfPosts() {
        $req = $this->db->prepare('SELECT COUNT(*) FROM posts WHERE published = 1');
        $req->execute();
        return $req->fetchColumn();
    }

    /**
     * @param $this_page_first_result
     * @param $results_per_page
     * @return array
     *
     * RECUPERER DES ARTICLES SELON LA PAGINATION
     */
    public function getPostsPagination($start, $results_per_page) {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image/*, o.content as comment/*, c.name as category*/
                          FROM posts p
                          INNER JOIN user u on p.user_id = u.id
                          INNER JOIN image i on p.img_id = i.id
                          /*LEFT JOIN comment o on p.id = o.post_id*/
                          /*INNER JOIN category_posts x on p.id = x.posts_id
                          LEFT JOIN category c on x.category_id = c.id*/
                          LIMIT :start, :results_per_page');
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUS LES ARTICLES
     */
    public function getAllPostsWithUsers() {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, p.content, p.published, p.created_at, u.username as author, i.url as image
                          FROM posts p
                          INNER JOIN user u on p.user_id = u.id
                          INNER JOIN image i on p.img_id = i.id
                          ');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER LES 3 ARTICLES LES PLUS COMMENTES
     */
    public function getMostSeens() {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image
                          FROM posts p
                          INNER JOIN user u on p.user_id = u.id
                          INNER JOIN image i on p.img_id = i.id
                          ORDER BY p.numberComments DESC
                          LIMIT 3
                          ');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UN ARTICLE
     */
    public function deletePost(int $id) {
        $req = $this->db->prepare('DELETE FROM posts WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }

    public function addNumberComment($id) {
        $req = $this->db->prepare('UPDATE posts
            SET numberComments = numberComments + 1
            WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();

    }

    public function minusNumberComment($id) {
        $req = $this->db->prepare('UPDATE posts
            SET numberComments = numberComments - 1
            WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();

    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE DE COMMENTAIRES PAR ARTICLES
     */
    public function getNumberOfComments() {
        $req = $this->db->prepare('SELECT COUNT(*) FROM (
            SELECT p.id, c.content as comment
            FROM posts p
            INNER JOIN comment c on p.id = c.post_id
            WHERE p.id = c.post_id
            ) as get_posts');
        $req->execute();
        return $req->fetchColumn();
    }
}
