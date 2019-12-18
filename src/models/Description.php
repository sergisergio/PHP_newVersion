<?php
namespace Models;
class Description extends Model
{
    public function getDescription() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM description d
                          INNER JOIN image i on d.image_id = i.id
                          WHERE d.image_id = 10');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
