<?php

namespace Models;

/**
 * CLASSE GERANT LA SECURITE
 */
class Security extends Model
{
/**
     * Function checkBruteForce
     *
     * @param string $ip IP address
     *
     * @return string
     */
    public function checkBruteForce($ip, $username)
    {
        $req = $this->db->prepare('
            SELECT * FROM connexion
            WHERE ip = :ip
            AND username = :username');
        $req->bindParam(':ip', $ip);
        $req->bindParam(':username', $username);
        $count = $req->execute();
        $count = $req->rowCount();
        return $count;
    }
    /**
     * Function registerAttempt
     *
     * @param string $ip IP address
     *
     * @return string
     */
    public function registerAttempt($ip, $username)
    {
        $req = $this->db->prepare('INSERT INTO connexion(ip, username) VALUES(:ip, :username)');
        $req->bindParam(':ip', $ip);
        $req->bindParam(':username', $username);
        $req->execute();
    }
}
