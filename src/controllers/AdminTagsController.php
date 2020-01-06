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
        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name' => $name,
                    'numberPosts'  => 0
                ];
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
    /**
     * METTRE A JOUR UNE ETIQUETTE
     */
    public function updateTag($title, $id) {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $id = strip_tags(htmlspecialchars($_POST['id']));


        if (isset($title) && isset($id)) {
            $this->tagModel->updateTag($title, $id);
            $this->msg->success("Le tag a bien été modifié !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UNE ETIQUETTE
     */
    public function deleteTag() {
        $tag['id'] = $_GET['id'];
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
}
