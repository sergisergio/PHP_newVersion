<?php

namespace Controllers;

use Models\Category;
use Service\SecurityService;

/**
 * classe AdminCategoryController
 *
 * Cette classe gère les catégories
 */
class AdminCategoryController extends Controller
{
    protected $categoryModel;
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
        $this->categoryModel = new Category;
        $this->securityService = new SecurityService;
    }
    /**
     * MODIFIER UNE CATEGORIE
     *
     * - récupère l'id de la catégorie et le titre via le formulaire
     * - vérifie que le token CSRF est bon
     * - modifie la catégorie dans la base de données
     */
    public function updateCategory() {
        $token = $_POST['update_category_token'];
        $session_token = $_SESSION['update_category_token'];
        $title = strip_tags(htmlspecialchars($_POST['title']));
        $id = strip_tags(htmlspecialchars($_POST['id']));

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($title) && isset($id)) {
                    $this->categoryModel->updateCategory($title, $id);
                    $this->msg->success("La catégorie a bien été modifiée !", $this->getUrl(true));
                }
            } else {
                $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * AJOUTER UNE CATEGORIE
     *
     * - récupère le nom de la catégorie via le formulaire
     * - vérifie que le token CSRF est bon
     * - ajoute la catégorie dans la base de données
     */
    public function addCategory() {
        $array = [
            "name"  => "",
            "error"     => "",
            "success"   => "",
            "isSuccess" => false
        ];

        $token = $_POST['add_category_token'];
        $session_token = $_SESSION['add_category_token'];
        $name = htmlspecialchars($_POST['name']);
        $data = ['name' => $name,'numberPosts' => 0];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->categoryModel->setCategory($data)) {
                    //$this->msg->success("La catégorie a bien été ajoutée !", $this->getUrl(true));
                    $array["success"] = "La catégorie a bien été ajoutée ! !";
                    $array["isSuccess"] = true;
                } else {
                    //$this->msg->error("La catégorie n'a pas pu être ajoutée.", $this->getUrl(true));
                    $array["error"] = "La catégorie n'a pas pu être ajoutée.";
                    $array["isSuccess"] = false;
                }
            } else {
                $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
        echo json_encode($array);
    }
    /**
     * SUPPRIMER UNE CATEGORIE
     *
     * - récupère l'id via méthode GET
     * - vérifie que le token CSRF est bon
     * - supprime la catégorie correspondant à l'identifiant dans la base de données
     */
    public function deleteCategory() {
        $array = [
            "name"  => "",
            "error"     => "",
            "success"   => "",
            "isSuccess" => false
        ];
        $token = $_POST['delete_category_token'];
        $session_token = $_SESSION['delete_category_token'];
        $category['id'] = $_GET['id'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->categoryModel->deleteCategory($category['id'])) {
                    $this->msg->success("La catégorie a bien été supprimée", $this->getUrl(true));
                    //$array["success"] = "La catégorie a bien été ajoutée ! !";
                    //$array["isSuccess"] = true;
                } else {
                    $this->msg->error("La catégorie n'a pas pu être supprimée", $this->getUrl(true));
                    //$array["success"] = "La catégorie n'a pas pu être supprimée !";
                    //$array["isSuccess"] = false;
                }
            } else {
                $this->msg->error("Une erreur est survenue.", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
        echo json_encode($array);
    }
}
