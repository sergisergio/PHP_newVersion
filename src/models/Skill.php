<?php
namespace Models;
class Skill extends Model
{
    public function getAllSkills() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM skill');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllSkills2() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM skill2');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
