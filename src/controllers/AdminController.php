<?php

namespace Controllers;

use Models\Description;
use Models\Skill;
use Service\SecurityService;

/**
 * classe AdminController
 *
 * Cette classe redirige vers le formulaire de connexion si l'utilisateur n'a pas de session admin
 */
class AdminController extends Controller
{
    protected $descriptionModel;
    protected $skillModel;
    protected $securityModel;
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
        $this->securityService = new SecurityService;
    }
    /**
     * METTRE A JOUR LA RUBRIQUE QUI-SUIS-JE ?
     *
     * - récupère titre et contenu via formulaire
     * - vérifie que le token est bon
     * - met à jour la description dans la base de données
     */
    public function updateAbout() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $content = html_entity_decode($_POST['content']);
        $session_token = $_SESSION['update_about_token'];
        $token = $_POST['update_about_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title) && isset($content)) {
                $this->descriptionModel->updateDescription($title, $content);
                header('Location: index.php#about');
                exit;
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UNE BARRE DE PROGRESSION
     *
     * - récupère une barre de progression en fonction de son identifiant
     * - vérifie que le token CSRF est bon
     * - met à jour la barre de progression
     */
    public function updateSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));
        $skillId = strip_tags(htmlspecialchars($_POST['id']));
        $session_token = $_SESSION['update_skill_token'];
        $token = $_POST['update_skill_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title) && isset($level) && isset($skillId)) {
                $this->skillModel->updateSkill($title, $level, $skillId);
                header('Location: index.php#skills');
                exit;
            } else {
            $this->msg->error("Champs vides !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE BARRE DE PROGRESSION
     *
     * - récupère titre et niveau via formulaire
     * - vérifie que le token CSRF est bon
     * - ajoute une barre de progression
     *
     */
    public function addSkill() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $level = intval(strip_tags(htmlspecialchars($_POST['level'])));
        $session_token = $_SESSION['add_skill_token'];
        $token = $_POST['add_skill_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title) && isset($level)) {
                $this->skillModel->addSkill($title, $level);
                header('Location: index.php#skills');
                exit;
            } else {
            $this->msg->error("Champs vides !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UEN BARRE DE PROGRESSION
     *
     * - récupère une barre de progression en fonction de son identifiant
     * - vérifie que le token CSRF est bon
     * - supprime la berre de prpogression de la base de données
     */
    public function deleteSkill() {
        $id = strip_tags(htmlspecialchars($_POST['skillId']));
        $session_token = $_SESSION['delete_skill_token'];
        $token = $_POST['delete_skill_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($id)) {
                $this->skillModel->deleteSkill($id);
                header('Location: index.php#skills');
                exit;
            } else {
            $this->msg->error("Aucun identifiant ne correspond !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE COMPETENCE
     *
     * - récupère nom et contenu via formulaire
     * - vérifie que le token CSRF est bon
     * - ajoute la compétence dans la base de données
     */
    public function addSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = strip_tags(htmlspecialchars($_POST['content']));
        $session_token = $_SESSION['add_skill2_token'];
        $token = $_POST['add_skill2_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($name) && isset($content)) {
                $this->skillModel->addSkill2($name, $content);
                header('Location: index.php#skills');
                exit;
            } else {
                $this->msg->error("Champs vides !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UNE COMPETENCE
     *
     * - récupère une compétence en fonction de son identifiant
     * - vérifie que le token CSRF est bon
     * - supprime la compétence de la base de données
     */
    public function deleteSkill2() {
        $id = strip_tags(htmlspecialchars($_POST['id']));
        $session_token = $_SESSION['delete_skill2_token'];
        $token = $_POST['delete_skill2_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($id)) {
                $this->skillModel->deleteSkill2($id);
                header('Location: index.php#skills');
                exit;
            } else {
                $this->msg->error("Identifiant inexistant !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UNE COMPETENCE
     *
     * - récupère uen compétence en fonction de son identifiant
     * - vérifie que le token CSRF est bon
     * - met à jour la compétence dans la base de données
     */
    public function updateSkill2() {
        $name = strip_tags(htmlspecialchars($_POST['name']));
        $content = html_entity_decode($_POST['content']);
        $id = strip_tags(htmlspecialchars($_POST['id']));
        $session_token = $_SESSION['update_skill2_token'];
        $token = $_POST['update_skill2_token'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($name) && isset($content) && isset($id)) {
                $this->skillModel->updateSkill2($name, $content, $id);
                header('Location: index.php#skills');
                exit;
            } else {
                $this->msg->error("Champs vides !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR LE SOUS_TITRE DU HEADER
     *
     * - récupère le titre
     * - vérifie que le token CSRF est bon
     * - met à jour le sous-titre du header dans la base de données
     */
    public function updateSubtitle() {
        $session_token = $_SESSION['update_subtitle'];
        $token = $_POST['update_subtitle'];
        $title = strip_tags(htmlspecialchars($_POST['title']));

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title)) {
                $this->descriptionModel->updateSubtitle($title);
                header('Location: index.php');
                exit;
            } else {
                $this->msg->error("Champ vide !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
