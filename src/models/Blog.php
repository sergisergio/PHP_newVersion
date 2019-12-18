<?php
namespace Models;
class Blog extends Model
{
    /**
     * @param $data
     * @return bool
     *
     * Create a blog post, return true if no errors
     */
    public function setPost($data) {
        $req = $this->db->prepare('
            INSERT INTO posts (title, subtitle, content, date_add, image, active, id_user)
            VALUES (:title, :subtitle, :content, NOW(), :image, :active, :id_user)');
        $req->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $req->bindValue(':subtitle', $data['subtitle'], \PDO::PARAM_STR);
        $req->bindValue(':content', $data['content'], \PDO::PARAM_LOB);
        $req->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_BOOL);
        $req->bindValue(':id_user', $data['id_user'], \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * @param $id
     * @return mixed
     *
     * Retrieve data from a blog post based on its ID
     */
    public function getPostById($id) {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, p.subtitle, p.content, p.image, p.active, p.date_add, p.date_update, u.name as author
                          FROM posts p INNER JOIN users u on p.id_user = u.id
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
     * Update blog post's data based on its ID
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
     * Get total number of blog posts
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
     * Get blog posts based on pagination
     */
    public function getPostsPagination($this_page_first_result, $results_per_page) {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, /*p.subtitle,*/ p.content, /*p.image,*/ p.published, p.created_at, /*p.date_update,*/ u.username as author
                          FROM posts p INNER JOIN user u on p.user_id = u.id
                          LIMIT :first_page, :results_per_page');
        $req->bindParam(':first_page', $this_page_first_result, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllPostsWithUsers() {
        $req = $this->db->prepare('
                          SELECT p.id, p.title, /*p.subtitle,*/ p.content, /*p.image,*/ p.published, p.created_at, /*p.date_update,*/ u.username as author
                          FROM posts p INNER JOIN user u on p.user_id = u.id');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * @param int $id
     * @return bool
     *
     * delete a post
     */
    public function deletePost(int $id) {
        $req = $this->db->prepare('DELETE FROM posts WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
