<?php

namespace Controllers;

use Models\Tag;

/**
 * classe AdminTagsController
 *
 * Cette classe gère les tags
 */
class AdminTagsController extends Controller
{
    protected $tagModel;
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
    }
    /**
     * AJOUTER UNE ETIQUETTE
     */
    public function addTag() {
        $token = $_SESSION['add_tag_token'];
        $add_tag_token = $_POST['add_tag_token'];

        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name' => $name,
                    'numberPosts'  => 0
                ];

        if (isset($token) AND isset($add_tag_token) AND !empty($token) AND !empty($add_tag_token)) {
            if ($token == $add_tag_token) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($this->tagModel->setTag($data)) {
                        $this->msg->success("Le tag a bien été ajouté !", $this->getUrl());
                    } else {
                        $this->msg->error("Le tag n'a pas pu être ajouté.", $this->getUrl());
                    }
                } else {
                    header('Location: ' . '?c=adminDashboard&t=tags');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * METTRE A JOUR UNE ETIQUETTE
     */
    public function updateTag($title, $id) {
        $token = $_SESSION['update_tag_token'];
        $update_tag_token = $_POST['update_tag_token'];

        $title = strip_tags(htmlspecialchars($_POST['title']));
        $id = strip_tags(htmlspecialchars($_POST['id']));

        if (isset($token) AND isset($update_tag_token) AND !empty($token) AND !empty($update_tag_token)) {
            if ($token == $update_tag_token) {
                if (isset($title) && isset($id)) {
                    $this->tagModel->updateTag($title, $id);
                    $this->msg->success("Le tag a bien été modifié !", $this->getUrl(true));
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UNE ETIQUETTE
     */
    public function deleteTag() {
        $token = $_SESSION['delete_tag_token'];
        $delete_tag_token = $_POST['delete_tag_token'];

        $tag['id'] = $_GET['id'];

        if (isset($token) AND isset($$delete_tag_token) AND !empty($token) AND !empty($delete_tag_token)) {
            if ($token == $delete_tag_token) {
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
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
