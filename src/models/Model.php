<?php
namespace Models;
use Config\Db;
class Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new Db;
    }
    /**
     * @return mixed
     *
     * Get the config
     */
    public function getConfig() {
        $req = $this->db->prepare('SELECT * FROM config WHERE id = 1');
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * @param int $ppp
     * @param int $cpc
     * @return bool
     *
     * Update the config
     */
    public function updateConfig(int $ppp, int $cpc) {
        $req = $this->db->prepare("UPDATE config SET ppp = :ppp, characters = :cpc WHERE id = 1");
        $req->bindValue(':ppp', $ppp, \PDO::PARAM_INT);
        $req->bindValue(':cpc', $cpc, \PDO::PARAM_INT);
        return $req->execute();
    }
}
