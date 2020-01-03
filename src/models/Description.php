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
            SELECT d.title, d.content, d.subtitle, i.url as image
            FROM description d
            INNER JOIN image i on d.image_id = i.id
            WHERE d.id = 1');
        $req->execute();
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateDescription($title, $content) {
        $req = $this->db->prepare("
            UPDATE description
            SET title = :title, content = :content
            WHERE id = 1");
        $req->bindValue(':title', $title, \PDO::PARAM_STR);
        $req->bindValue(':content', $content, \PDO::PARAM_STR);
        return $req->execute();
    }

    public function updateSubtitle($title) {
        $req = $this->db->prepare("
            UPDATE description
            SET subtitle = :subtitle
            WHERE id = 1");
        $req->bindValue(':subtitle', $title, \PDO::PARAM_STR);
        return $req->execute();
    }
}
