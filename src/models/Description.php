<?php

namespace Models;

/**
 * CLASSE GERANT LA DESCRIPTION
 */
class Description extends Model
{
    /**
     * RECUPERER LA DESCRIPTION
     */
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
