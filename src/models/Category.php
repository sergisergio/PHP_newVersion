<?php
namespace Models;
class Category extends Model
{
    public function getAllCategories() {
        $req = $this->db->prepare('
                          SELECT *
                          FROM category');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
}
