<?php

namespace Controllers;

use Models\Description;
use Models\Skill;

/**
 * classe AdminController
 *
 * Cette classe redirige vers le formulaire de connexion si l'utilisateur n'a pas de session admin
 */
class AdminController extends Controller
{
    protected $descriptionModel;
    protected $skillModel;
    /**
     * Constructeur
     *
     * REDIRIGE VERS LE FORMULAIRE DE CONNEXION SI LE MEMBRE N'EST PAS ADMINISTRATEUR
     */
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAdmin()) {
            header('Location: ?c=login');
            exit;
        }
        $this->descriptionModel = new Description;
        $this->skillModel = new Skill;
    }
    /**
     * METTRE A JOUR LA RUBRIQUE QUI-SUIS-JE ?
     */
    public function updateAbout() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $content = strip_tags(htmlspecialchars($_POST['content']));

        if (isset($title) && isset($content)) {
            $this->descriptionModel->updateDescription($title, $content);
            header('Location: index.php#about');
            exit;
        }
    }
    /**
     * METTRE A JOUR UNE BARRE DE PROGRESSION
     */
    public function updateSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));
        $skillId = strip_tags(htmlspecialchars($_POST['id']));


        if (isset($title) && isset($level) && isset($skillId)) {
            $this->skillModel->updateSkill($title, $level, $skillId);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * AJOUTER UNE BARRE DE PROGRESSION
     */
    public function addSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));


        if (isset($title) && isset($level)) {
            $this->skillModel->addSkill($title, $level);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * SUPPRIMER UEN BARRE DE PROGRESSION
     */
    public function deleteSkill() {
        $id = strip_tags(htmlspecialchars($_POST['skillId']));
        if (isset($id)) {
            $this->skillModel->deleteSkill($id);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * AJOUTER UNE COMPETENCE
     */
    public function addSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = strip_tags(htmlspecialchars($_POST['content']));


        if (isset($name) && isset($content)) {
            $this->skillModel->addSkill2($name, $content);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * SUPPRIMER UEN COMPETENCE
     */
    public function deleteSkill2() {
        $id = strip_tags(htmlspecialchars($_POST['id']));
        if (isset($id)) {
            $this->skillModel->deleteSkill2($id);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * METTRE A JOUR UEN COMPETENCE
     */
    public function updateSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = strip_tags(htmlspecialchars($_POST['content']));
        $id = strip_tags(htmlspecialchars($_POST['id']));
        if (isset($name) && isset($content) && isset($id)) {
            $this->skillModel->updateSkill2($name, $content, $id);
            header('Location: index.php#skills');
            exit;
        }
    }
    /**
     * METTRE A JOUR LE SOUS_TITRE DU HEADER
     */
    public function updateSubtitle() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        if (isset($title)) {
            $this->descriptionModel->updateSubtitle($title);
            header('Location: index.php');
            exit;
        }
    }
}
