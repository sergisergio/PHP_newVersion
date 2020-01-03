<?php

namespace Models;

/**
 * CLASSE GERANT LES COMPETENCES
 */
class Skill extends Model
{
    /**
     * RECUPERER TOUTES LES COMPETENCES ((PROGRESS BAR / PARTIE GAUCHE))
     */
    public function getAllSkills() {
        $req = $this->db->prepare('
            SELECT *
            FROM skill');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * RECUPERER TOUTES LES COMPETENCES ((PARTIE DROITE))
     */
    public function getAllSkills2() {
        $req = $this->db->prepare('
            SELECT *
            FROM skill2');
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateSkill($title, $level, $id) {
        $req = $this->db->prepare("
            UPDATE skill
            SET name = :title, level = :level
            WHERE id = :id");
        $req->bindValue(':title', $title, \PDO::PARAM_STR);
        $req->bindValue(':level', $level, \PDO::PARAM_INT);
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }

    public function addSkill($title, $level) {
        $req = $this->db->prepare('
            INSERT INTO skill (name, level)
            VALUES (:title, :level)');
        $req->bindValue(':title', $title, \PDO::PARAM_STR);
        $req->bindValue(':level', $level, \PDO::PARAM_INT);
        return $req->execute();
    }

    public function deleteSkill($id) {
        $req = $this->db->prepare('
            DELETE FROM skill WHERE id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }

    public function addSkill2($name, $content) {
        $req = $this->db->prepare('
            INSERT INTO skill2 (name, content)
            VALUES (:name, :content)');
        $req->bindValue(':name', $name, \PDO::PARAM_STR);
        $req->bindValue(':content', $content, \PDO::PARAM_LOB);
        return $req->execute();
    }

    public function updateSkill2($name, $content, $id) {
        $req = $this->db->prepare("
            UPDATE skill2
            SET name = :name, content = :content
            WHERE id = :id");
        $req->bindValue(':name', $name, \PDO::PARAM_STR);
        $req->bindValue(':content', $content, \PDO::PARAM_LOB);
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }

    public function deleteSkill2($id) {
        $req = $this->db->prepare('
            DELETE FROM skill2 WHERE id = :id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}
