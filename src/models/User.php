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
        $req = $this->db->prepare('SELECT * FROM user');
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
    public function getUser($username, $password) {
        $data = [
            'username'     => $username,
            'password'  => sha1($password) // encode the password
        ];
        $req = $this->db->prepare('
            SELECT username, id, roles
            FROM user
            WHERE username = :username
            AND password = :password');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->bindValue(':password', $data['password'], \PDO::PARAM_STR);
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
            INSERT INTO user (username, email, password, roles, active, banned, created_at, ip_address, token)
            VALUES (:username, :email, :password, :roles, :active, :banned, :created_at, :ip_address, :token)');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        $req->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $req->bindValue(':roles', $data['roles'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_INT);
        $req->bindValue(':banned', $data['banned'], \PDO::PARAM_INT);
        $req->bindValue(':created_at', $data['created_at']);
        $req->bindValue(':ip_address', $data['ip_address']);
        $req->bindValue(':token', $data['token']);
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
        $req = $this->db->prepare('SELECT * FROM user WHERE email = :email LIMIT 1');
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
        $req = $this->db->prepare('SELECT * FROM user WHERE username = :username LIMIT 1');
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
        $req = $this->db->prepare('SELECT * FROM user WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
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
        $req = $this->db->prepare('DELETE FROM user WHERE id = :id LIMIT 1');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
