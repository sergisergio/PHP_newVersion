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
        $update_category_token = $_POST['update_category_token'];

        if (isset($_SESSION['update_category_token']) AND isset($update_category_token) AND !empty($_SESSION['update_category_token']) AND !empty($update_category_token)) {
            if ($_SESSION['update_category_token'] == $update_category_token) {
                $title = strip_tags(htmlspecialchars($_POST['title']));
                $id = strip_tags(htmlspecialchars($_POST['id']));
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($title) && isset($id)) {
                        $this->categoryModel->updateCategory($title, $id);
                        $this->msg->success("La catégorie a bien été modifiée !", $this->getUrl(true));
                    }
                }
            } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE CATEGORIE
     */
    public function addCategory() {
        $add_category_token = $_POST['add_category_token'];

        $name = htmlspecialchars($_POST['name']);
        $data = ['name'         => $name,'numberPosts'  => 0];

        if (isset($_SESSION['add_category_token']) AND isset($add_category_token) AND !empty($_SESSION['add_category_token']) AND !empty($add_category_token)) {


            if ($_SESSION['add_category_token'] == $add_category_token) {
                echo 'OK';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($this->categoryModel->setCategory($data)) {
                        $this->msg->success("La catégorie a bien été ajoutée !", $this->getUrl(true));
                    } else {
                        $this->msg->error("La catégorie n'a pas pu être ajoutée.", $this->getUrl(true));
                    }
                } else {
                    $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
                }
            } else {
                    $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UNE CATEGORIE
     */
    public function deleteCategory() {
        $delete_category_token = $_POST['delete_category_token'];

        $category['id'] = $_GET['id'];

        if (isset($_SESSION['delete_category_token']) AND isset($delete_category_token) AND !empty($_SESSION['delete_category_token']) AND !empty($delete_category_token)) {
            if ($_SESSION['delete_category_token'] == $delete_category_token) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if ($this->categoryModel->deleteCategory($category['id'])) {
                        $this->msg->success("La catégorie a bien été supprimée", $this->getUrl(true));
                    } else {
                        $this->msg->error("La catégorie n'a pas pu être supprimée", $this->getUrl(true));
                    }
                } else {
                    $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
                }
            } else {
                $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
}
