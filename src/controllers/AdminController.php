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
     *
     * protection CSRF effectuée
     */
    public function updateAbout() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $content = strip_tags(htmlspecialchars($_POST['content']));
        $token = $_SESSION['token'];
        $update_about_token = $_POST['update_about_token'];

        if (isset($token) AND isset($update_about_token) AND !empty($token) AND !empty($update_about_token)) {
            if ($token == $update_about_token) {
                if (isset($title) && isset($content)) {
                    $this->descriptionModel->updateDescription($title, $content);
                    header('Location: index.php#about');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UNE BARRE DE PROGRESSION
     */
    public function updateSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));
        $skillId = strip_tags(htmlspecialchars($_POST['id']));
        $token = $_SESSION['update_skill_token'];
        $update_skill_token = $_POST['update_skill_token'];

        if (isset($token) AND isset($update_skill_token) AND !empty($token) AND !empty($update_skill_token)) {
            if ($token == $update_skill_token) {
                if (isset($title) && isset($level) && isset($skillId)) {
                    $this->skillModel->updateSkill($title, $level, $skillId);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE BARRE DE PROGRESSION
     */
    public function addSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));
        $token = $_SESSION['add_skill_token'];
        $add_skill_token = $_POST['add_skill_token'];

        if (isset($token) AND isset($add_skill_token) AND !empty($token) AND !empty($add_skill_token)) {
            if ($token == $add_skill_token) {
                if (isset($title) && isset($level)) {
                    $this->skillModel->addSkill($title, $level);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UEN BARRE DE PROGRESSION
     */
    public function deleteSkill() {
        $id = strip_tags(htmlspecialchars($_POST['skillId']));
        $token = $_SESSION['delete_skill_token'];
        $delete_skill_token = $_POST['delete_skill_token'];

        if (isset($token) AND isset($delete_skill_token) AND !empty($token) AND !empty($delete_skill_token)) {
            if ($token == $delete_skill_token) {
                if (isset($id)) {
                    $this->skillModel->deleteSkill($id);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE COMPETENCE
     */
    public function addSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = strip_tags(htmlspecialchars($_POST['content']));
        $token = $_SESSION['add_skill2_token'];
        $add_skill2_token = $_POST['add_skill2_token'];

        if (isset($token) AND isset($add_skill2_token) AND !empty($token) AND !empty($add_skill2_token)) {
            if ($token == $_POST['add_skill2_token']) {
                if (isset($name) && isset($content)) {
                    $this->skillModel->addSkill2($name, $content);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UEN COMPETENCE
     */
    public function deleteSkill2() {
        $id = strip_tags(htmlspecialchars($_POST['id']));
        $token = $_SESSION['delete_skill2_token'];
        $delete_skill2_token = $_POST['delete_skill2_token'];

        if (isset($token) AND isset($delete_skill2_token) AND !empty($token) AND !empty($delete_skill2_token)) {
            if ($token == $delete_skill2_token) {
                if (isset($id)) {
                    $this->skillModel->deleteSkill2($id);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UEN COMPETENCE
     */
    public function updateSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = strip_tags(htmlspecialchars($_POST['content']));
        $id = strip_tags(htmlspecialchars($_POST['id']));
        $token = $_SESSION['update_skill2_token'];
        $update_skill2_token = $_POST['update_skill2_token'];

        if (isset($token) AND isset($update_skill2_token) AND !empty($token) AND !empty($update_skill2_token)) {
            if ($token == $update_skill2_token) {
                if (isset($name) && isset($content) && isset($id)) {
                    $this->skillModel->updateSkill2($name, $content, $id);
                    header('Location: index.php#skills');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR LE SOUS_TITRE DU HEADER
     */
    public function updateSubtitle() {
        $token = $_SESSION['update_subtitle'];
        $update_subtitle_token = $_POST['update_subtitle'];
        $title = strip_tags(htmlspecialchars($_POST['title']));

        if (isset($token) AND isset($update_subtitle_token) AND !empty($token) AND !empty($update_subtitle_token)) {
            if ($token == $update_subtitle_token) {
                if (isset($title)) {
                    $this->descriptionModel->updateSubtitle($title);
                    header('Location: index.php');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
