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
            INSERT INTO posts (title, content, created_at, user_id, img_id, published, numberComments)
            VALUES (:title, :content, NOW(), :user_id, :img_id, :published, :numberComments)');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $req->bindValue(':img_id', $data['img_id'], \PDO::PARAM_INT);
        $req->bindValue(':published', $data['published'], \PDO::PARAM_INT);
        $req->bindValue(':numberComments', $data['numberComments'], \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * @param $data
     * @return bool
     *
     * MODIFIER UN ARTICLE
     */
    public function updatePost($data) {
        $req = $this->db->prepare('
            UPDATE posts
            SET title = :title, content = :content, img_id = :img_id, published = :published
            WHERE id = :id');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':img_id', $data['img_id'], \PDO::PARAM_INT);
        $req->bindValue(':published', $data['published'], \PDO::PARAM_INT);
        $req->bindValue(':id', $data['id'], \PDO::PARAM_INT);
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
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author,
            i.id as img_id, i.url as image, c.category_id as category
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN category_posts c on p.id = c.posts_id
            WHERE p.id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @param $id
     * @return mixed
     *
     * RECUPERE UN ARTICLE AVEC SON ID
     */
    public function getLastId() {
        return $this->db->lastInsertId();
    }
    /**
     * @param $data
     * @param $postId
     * @return bool
     *
     * METTRE A JOUR UN ARTICLE AVEC SON ID
     */
    /*public function updatePost($data, $postId) {
        $req = $this->db->prepare('
            UPDATE posts
            SET title = :title, subtitle = :subtitle, content = :content, image = :image, active = :active, date_update = NOW()
            WHERE id = :id
            LIMIT 1');
        $req->bindValue(':id', $postId, \PDO::PARAM_INT);
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':subtitle', $data['subtitle'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_BOOL);
        return $req->execute();
    }*/
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE D'ARTICLES
     */
    public function getNumberOfPosts() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM posts
            WHERE published = 1');
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE D'ARTICLES
     */
    public function getNumber() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM posts');
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
            SELECT DISTINCT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, c.name as category
            FROM posts p
            LEFT JOIN user u on p.user_id = u.id
            LEFT JOIN image i on p.img_id = i.id
            LEFT JOIN category_posts x on p.id = x.posts_id
            LEFT JOIN category c on x.category_id = c.id
            ORDER BY p.created_at DESC
            LIMIT :start, :results_per_page');
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTagsPerPost() {
        $req = $this->db->prepare('
            SELECT p.id, t.name as tag
            FROM posts p
            LEFT JOIN tag_posts a on p.id = a.posts_id
            LEFT JOIN tag t  on t.id = a.tag_id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
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
            INNER JOIN image i on p.img_id = i.id');
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
            LIMIT 3');
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
        $req = $this->db->prepare('
            DELETE FROM posts
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * INCREMENTER LE NOMBRE DE COMMENTAIRES D'UN ARTICLE
     */
    public function addNumberComment($id) {
        $req = $this->db->prepare('
            UPDATE posts
            SET numberComments = numberComments + 1
            WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();

    }
    /**
     * DECREMENTER LE NOMBRE DE COMMENTAIRES D'UN ARTICLE
     */
    public function minusNumberComment($id) {
        $req = $this->db->prepare('
            UPDATE posts
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
        $req = $this->db->prepare('
            SELECT COUNT(*) FROM (
                SELECT p.id, c.content as comment
                FROM posts p
                INNER JOIN comment c on p.id = c.post_id
                WHERE p.id = c.post_id
            ) as get_posts');
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * RECUPERER TOUS LES ARTICLES AVEC LA RECHERCHE
     */
    public function searchRequest($search) {
        $req = $this->db->prepare("
            SELECT * FROM posts
            WHERE content
            LIKE '%$search%'
            ORDER BY id DESC");
        //$req->bindValue(':search', $search, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LE NOMBRE D'ARTICLES EN RAPPORT AVEC LA RECHERCHE
     */
    public function countSearchRequest($search) {
        $req = $this->db->prepare("
            SELECT COUNT(*) FROM posts
            WHERE content
            LIKE '%$search%'");
        //$req->bindValue(':search', $search, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * @param $this_page_first_result
     * @param $results_per_page
     * @return array
     *
     * RECUPERER DES ARTICLES SELON LA RECHERCHE LA PAGINATION
     */
    public function getSearchPagination($search, $start, $results_per_page) {
        $req = $this->db->prepare("
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, c.name as category
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN category_posts x on p.id = x.posts_id
            LEFT JOIN category c on x.category_id = c.id
            WHERE p.content
            LIKE '%$search%'
            LIMIT :start, :results_per_page");
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LES ARTICLES EN RAPPORT AVEC LA CATEGORIE DEMANDEE
     */
    public function searchByCategory($category) {
        $req = $this->db->prepare("
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, c.name as category
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN category_posts x on p.id = x.posts_id
            LEFT JOIN category c on x.category_id = c.id
            WHERE c.name = :category");
        $req->bindParam(':category', $category, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LES ARTICLES EN RAPPORT AVEC LA CATEGORIE DEMANDEE ET PAGINATION
     */
    public function getCategoryPagination($category, $start, $results_per_page) {
        $req = $this->db->prepare("
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, c.name as category
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN category_posts x on p.id = x.posts_id
            LEFT JOIN category c on x.category_id = c.id
            WHERE c.name = :category
            LIMIT :start, :results_per_page");
        $req->bindParam(':category', $category, \PDO::PARAM_STR);
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LE NOMBRE D'ARTICLES EN RAPPORT A LA CATEGORIE DEMANDEE
     */
    public function countSearchByCategoryRequest($category) {
        $req = $this->db->prepare("
            SELECT COUNT(*)
            FROM posts p
            LEFT JOIN category_posts x on p.id = x.posts_id
            LEFT JOIN category c on x.category_id = c.id
            WHERE c.name = :category");
        $req->bindParam(':category', $category, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchColumn();
    }
    /**
     * RETOURNE LES ARTICLES EN RAPPORT AVEC LE TAG DEMANDE
     */
    public function searchByTag($tag) {
        $req = $this->db->prepare("
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, x.name as tag
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN tag_posts t on p.id = t.posts_id
            LEFT JOIN tag x on x.id = t.tag_id
            WHERE x.name = :tag");
        $req->bindParam(':tag', $tag, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LES ARTICLES EN RAPPORT AVEC LE TAG DEMANDE ET PAGINATION
     */
    public function getTagPagination($tag, $start, $results_per_page) {
        $req = $this->db->prepare("
            SELECT p.id, p.title, p.content, p.published, p.created_at, p.numberComments, u.username as author, i.url as image, x.name as tag
            FROM posts p
            INNER JOIN user u on p.user_id = u.id
            INNER JOIN image i on p.img_id = i.id
            INNER JOIN tag_posts t on p.id = t.posts_id
            LEFT JOIN tag x on x.id = t.tag_id
            WHERE x.name = :tag
            LIMIT :start, :results_per_page");
        $req->bindParam(':tag', $tag, \PDO::PARAM_STR);
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * RETOURNE LE NOMBRE D'ARTICLES EN RAPPORT AVEC LE TAG DEMANDE
     */
    public function countSearchByTagRequest($tag) {
        $req = $this->db->prepare("
            SELECT COUNT(*)
            FROM posts p
            INNER JOIN tag_posts x on p.id = x.posts_id
            LEFT JOIN tag t on x.tag_id = t.id
            WHERE t.name = :tag");
        $req->bindParam(':tag', $tag, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetchColumn();
    }

}
