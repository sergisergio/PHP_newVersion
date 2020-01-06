<?php

namespace Models;

/**
 * CLASSE GERANT LES MEMBRES
 */
class User extends Model
{
    /**
     * RECUPERER TOUS LES UTILISATEURS
     */
    public function getAllUsers() {
        $req = $this->db->prepare('
            SELECT u.id, u.email, u.roles, u.username, u.active, u.banned, u.created_at, i.url as image
            FROM user u
            INNER JOIN image i ON u.avatar_id = i.id');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $email
     * @param $password
     * @return mixed
     *
     * RECUPERER UN UTILISATEUR EN FONCTION DE SON PSEUDO ET DE SON MDP
     */
    public function getUser($username) {
        $req = $this->db->prepare('
            SELECT *
            FROM user u
            LEFT JOIN image i ON i.id = u.avatar_id
            WHERE u.username = :username');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return bool
     *
     * CREER UN MEMBRE
     */
    public function setUser($data) {
        $req = $this->db->prepare('
            INSERT INTO user (username, email, password, roles, active, banned, created_at, ip_address, token, token_created_date, token_expire_date, avatar_id)
            VALUES (:username, :email, :password, :roles, :active, :banned, :created_at, :ip_address, :token, NOW(), NOW() + INTERVAL 1 DAY, :avatar_id)');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        $req->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $req->bindValue(':roles', $data['roles'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_INT);
        $req->bindValue(':banned', $data['banned'], \PDO::PARAM_INT);
        $req->bindValue(':created_at', $data['created_at']);
        $req->bindValue(':ip_address', $data['ip_address']);
        $req->bindValue(':avatar_id', $data['avatar_id'], \PDO::PARAM_INT);
        $req->bindValue(':token', $data['token']);
        return $req->execute();
    }
    /**
     * ACTIVER UN MEMBRE
     */
    public function setUserActive($username) {
        $req = $this->db->prepare("
            UPDATE user
            SET active = 1, token = NULL, token_created_date = NULL, token_expire_date = NULL
            WHERE username = :username");
        $req->bindValue(':username', $username, \PDO::PARAM_STR);
        return $req->execute();
    }
    /**
     * METTRE A JOUR UN TOKEN
     */
    public function updateToken($username, $token) {
        $req = $this->db->prepare("
            UPDATE user
            SET token = :token, token_created_date = NOW(), token_expire_date = NOW() + INTERVAL 1 DAY
            WHERE username = :username");
        $req->bindValue(':username', $username, \PDO::PARAM_STR);
        $req->bindValue(':token', $token, \PDO::PARAM_STR);
        return $req->execute();
    }

    /**
     * @param $data
     * @return bool
     *
     * BLOQUER UN MEMBRE
     */
    public function banUser($username) {
        $req = $this->db->prepare('
            UPDATE user
            SET banned = 1
            WHERE username = :username');
        $req->bindValue(':username', $username, \PDO::PARAM_STR);
        return $req->execute();
    }

    /**
     * @param $email
     * @return int
     *
     * VERIFIER SI UN MEMBRE EXISTE EN FONCTIO DE SON EMAIL
     */
    public function checkUserByEmail ($email) {
        $req = $this->db->prepare('
            SELECT *
            FROM user
            WHERE email = :email
            LIMIT 1');
        $req->bindValue(':email', $email, \PDO::PARAM_STR);
        $req->execute();
        return $req->rowCount();
    }

    /**
     * @param $email
     * @return int
     *
     * VERIFIER SI UN MEMBRE EXISTE EN FONCTIO DE SON PSEUDO
     */
    public function checkUserByUsername ($username) {
        $req = $this->db->prepare('
            SELECT *
            FROM user
            WHERE username = :username
            LIMIT 1');
        $req->bindValue(':username', $username, \PDO::PARAM_STR);
        $req->execute();
        return $req->rowCount();
    }

    /**
     * @param $id
     * @return mixed
     *
     * RECUPERER UN MEMBRE EN FONCTION DE SON ID
     */
    public function getUserById ($id) {
        $req = $this->db->prepare('
            SELECT *
            FROM user
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     * @return mixed
     *
     * RECUPERER UN MEMBRE EN FONCTION DE SON ID
     */
    public function getUserByUsernameOrEmail ($username) {
        $req = $this->db->prepare('
            SELECT u.id, u.email, u.password, u.roles, u.username, u.active, u.token, u.token_created_date, u.token_expire_date, u.banned, u.created_at, i.url as image
            FROM user u
            INNER JOIN image i ON u.avatar_id = i.id
            WHERE username = :username
            OR email = :username
            LIMIT 1');
        $req->bindParam(':username', $username, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @param int $id
     * @return bool
     *
     * SUPPRIMER UN MEMBRE
     */
    public function deleteUser(int $id) {
        $req = $this->db->prepare('
            DELETE
            FROM user
            WHERE id = :id
            LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * METTRE A JOUR UN MEMBRE
     */
    public function updateUser($role, $active, $banned, $id) {
        $req = $this->db->prepare('
            UPDATE user
            SET roles = :role, active = :active, banned = :banned
            WHERE id = :id');
        $req->bindValue(':role', $role, \PDO::PARAM_INT);
        $req->bindValue(':active', $active, \PDO::PARAM_INT);
        $req->bindValue(':banned', $banned, \PDO::PARAM_INT);
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
    /**
     * @return mixed
     *
     * RECUPERER LE NOMBRE D'ARTICLES
     */
    public function getNumberOfUsers() {
        $req = $this->db->prepare('
            SELECT COUNT(*)
            FROM user');
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
    public function getUsersPagination($start, $results_per_page) {
        $req = $this->db->prepare('
            SELECT u.id, u.email, u.roles, u.username, u.active, u.banned, u.created_at, i.url as image
            FROM user u
            INNER JOIN image i ON u.avatar_id = i.id
            ORDER BY u.username ASC
            LIMIT :start, :results_per_page');
        $req->bindParam(':start', $start, \PDO::PARAM_INT);
        $req->bindParam(':results_per_page', $results_per_page, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
