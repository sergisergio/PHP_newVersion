<?php
namespace Models;
class Comment extends Model
{
    public function getAllComments() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM comment');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
