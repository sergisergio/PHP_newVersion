<?php
namespace Models;
class User extends Model
{
    public function getAllUsers() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM user');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $email
     * @param $password
     * @return mixed
     *
     * Get the user according to the selected email and password
     */
    public function getUser($username, $password) {
        $data = [
            'username'     => $username,
            'password'  => sha1($password) // encode the password
        ];
        $req = $this->db->prepare('SELECT username, id, roles FROM user WHERE username = :username AND password = :password');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return bool
     *
     * Creates a user
     */
    public function setUser($data) {
        $req = $this->db->prepare('INSERT INTO user (username, email, password, roles, active, created_at) VALUES (:username, :email, :password, :roles, :active, :created_at)');
        $req->bindValue(':username', $data['username'], \PDO::PARAM_STR);
        $req->bindValue(':email', $data['email'], \PDO::PARAM_STR);
        $req->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $req->bindValue(':roles', $data['roles'], \PDO::PARAM_STR);
        $req->bindValue(':active', $data['active'], \PDO::PARAM_INT);
        $req->bindValue(':created_at', $data['created_at']);
        return $req->execute();
    }

    /**
     * @param $email
     * @return int
     *
     * Check if the user exists according to the email
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
     * Check if the user exists according to the email
     */
    public function checkUserByUsername ($username) {
        $req = $this->db->prepare('SELECT * FROM user WHERE username = :username LIMIT 1');
        $req->bindValue(':username', $username, \PDO::PARAM_STR);
        $req->execute();
        return $req->rowCount();
    }
}
