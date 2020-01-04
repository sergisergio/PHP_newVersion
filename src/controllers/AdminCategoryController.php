<?php

namespace Controllers;

use Models\Category;

/**
 * classe AdminCategoryController
 *
 * Cette classe gère les catégories
 */
class AdminCategoryController extends Controller
{
    protected $categoryModel;


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
        $this->categoryModel = new Category;
    }
    /**
     * MODIFIER UNE CATEGORIE
     */
    public function updateCategory() {
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $id = strip_tags(htmlspecialchars($_POST['id']));
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($title) && isset($id)) {
                $this->categoryModel->updateCategory($title, $id);
                //header('Location: index.php?c=adminDashboard&t=categories');
                //exit;
                $this->msg->success("La catégorie a bien été modifiée !", $this->getUrl());
            }
        }
    }
    /**
     * AJOUTER UNE CATEGORIE
     */
    public function addCategory() {
        $name = htmlspecialchars($_POST['name']);
        $data = [
                    'name'         => $name,
                    'numberPosts'  => 0
                ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->categoryModel->setCategory($data)) {
                $this->msg->success("La catégorie a bien été ajoutée !", $this->getUrl());
            } else {
                $this->msg->error("La catégorie n'a pas pu être ajoutée.", $this->getUrl());
            }
        } else {
            header('Location: ' . '?c=adminDashboard&t=categories');
            exit;
        }
    }
    /**
     * SUPPRIMER UNE CATEGORIE
     */
    public function deleteCategory() {
        $category['id'] = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->categoryModel->deleteCategory($category['id'])) {
                    $this->msg->success("La catégorie a bien été supprimée", $this->getUrl());
                } else {
                    $this->msg->error("La catégorie n'a pas pu être supprimée", $this->getUrl());
                }
        } else {
            header('Location: ?c=adminDashboard&t=categories');
            exit;
        }
    }
}
