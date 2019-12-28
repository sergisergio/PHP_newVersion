<?php

namespace Models;

/**
 * CLASSE GERANT LA CONFIGURATION
 */
class Config extends Model
{
    /**
     * @return mixed
     *
     * RECUPERER LA CONFIGURATION
     */
    public function getConfig() {
        $req = $this->db->prepare('
            SELECT *
            FROM config
            WHERE id = 1');
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @param int $ppp
     * @param int $cpc
     * @return bool
     *
     * METTRE A JOUR LA CONFIGURATION
     */
    public function updateConfig(int $ppp, int $cpc) {
        $req = $this->db->prepare("
            UPDATE config
            SET ppp = :ppp, characters = :cpc
            WHERE id = 1");
        $req->bindValue(':ppp', $ppp, \PDO::PARAM_INT);
        $req->bindValue(':cpc', $cpc, \PDO::PARAM_INT);
        return $req->execute();
    }
}
