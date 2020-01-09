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
            WHERE ip = :ip');
        $req->bindParam(':ip', $ip);
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
        $req = $this->db->prepare('
            INSERT INTO connexion(ip, username, tried_at, tried_at_plus_one_day)
            VALUES(:ip, :username, NOW(), NOW() + INTERVAL 1 DAY)');
        $req->bindParam(':ip', $ip);
        $req->bindParam(':username', $username);
        $req->execute();
    }

    public function getAttempts($ip) {
        $req = $this->db->prepare('
            SELECT * FROM connexion
            WHERE ip = :ip');
        $req->bindParam(':ip', $ip);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteAttempts($ip) {
        $req = $this->db->prepare('
            DELETE FROM connexion
            WHERE ip = :ip');
        $req->bindParam(':ip', $ip);
        $req->execute();
    }
}
