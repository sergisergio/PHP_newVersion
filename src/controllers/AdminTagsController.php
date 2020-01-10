<?php

namespace Controllers;

use Models\Tag;
use Service\SecurityService;

/**
 * classe AdminTagsController
 *
 * Cette classe gère les tags
 */
class AdminTagsController extends Controller
{
    protected $tagModel;
    protected $securityService;
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
        $this->tagModel = new Tag;
        $this->securityService = new SecurityService;
    }
    /**
     * AJOUTER UNE ETIQUETTE
     *
     * - récupère le nom de l'étiquette
     * - vérifie que le token CSRF est bon
     * - ajoute l'étiquette en base de données
     */
    public function addTag() {
        $session_token = $_SESSION['add_tag_token'];
        $token = $_POST['add_tag_token'];
        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name' => $name,
                    'numberPosts'  => 0
                ];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->tagModel->setTag($data)) {
                    $this->msg->success("Le tag a bien été ajouté !", $this->getUrl());
                } else {
                    $this->msg->error("Le tag n'a pas pu être ajouté.", $this->getUrl());
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UNE ETIQUETTE
     *
     * - récupère identifiant et titre
     * - vérifie que le token CSRF est bon
     * - met à jour l'étiquette en base de données
     */
    public function updateTag($title, $id) {
        $session_token = $_SESSION['update_tag_token'];
        $token = $_POST['update_tag_token'];
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $id = strip_tags(htmlspecialchars($_POST['id']));

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title) && isset($id)) {
                $this->tagModel->updateTag($title, $id);
                $this->msg->success("Le tag a bien été modifié !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UNE ETIQUETTE
     *
     * - récupère l'identifiant de l'étiquette
     * - vérifie que le token CSRF est bon
     * - supprime l'étiquette de la base de données
     */
    public function deleteTag() {
        $session_token = $_SESSION['delete_tag_token'];
        $token = $_POST['delete_tag_token'];
        $tag['id'] = $_GET['id'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->tagModel->deleteTag($tag['id'])) {
                    $this->msg->success("Le tag a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("Le tag n'a pas pu être supprimé", $this->getUrl());
                }
            } else {
                header('Location: ?c=adminDashboard&t=tags');
                exit;
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
