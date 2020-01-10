<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\Image;
use Models\Tag;
use Models\Project;
use Service\UploadService;
use Service\SecurityService;

/**
 * classe AdminProjectsController
 *
 * Cette classe gère les projets
 */
class AdminProjectsController extends Controller
{
    protected $categoryModel;
    protected $blogModel;
    protected $imageModel;
    protected $tagModel;
    protected $projectModel;
    protected $uploadService;
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
        $this->categoryModel = new Category;
        $this->blogModel = new Blog;
        $this->imageModel = new Image;
        $this->tagModel = new Tag;
        $this->projectModel = new Project;
        $this->uploadService = new UploadService;
        $this->securityService = new SecurityService;
    }

    /**
     * AJOUTER UN PROJET
     *
     * - récupère titre, lien, contenu, catégorie, image et statut
     * - vérifie que le token CSRF est bon
     * - ajoute l'image dans un dossier propre au projet
     * - ajoute le projet
     * - ajoute une catégorie liée au projet
     */
    public function addProject() {
        $session_token = $_SESSION['add_project_token'];
        $token = $_POST['add_project_token'];
        $title = htmlspecialchars($_POST['title']);
        $link = htmlspecialchars($_POST['link']);
        $content =  html_entity_decode($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $published = htmlspecialchars($_POST['published']);
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];


        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $imageId = $this->uploadService->uploadProject($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image, $title);
                $data = [
                    'title'         => $title,
                    'link'          => $link,
                    'content'       => $content,
                    'img_id'        => $imageId,
                    'published'     => $published,
                    ];

                if ($this->projectModel->setProject($data)) {
                    $last_id = $this->projectModel->getLastId();
                    $this->categoryModel->addCategoryToProject($category, $last_id);
                    $this->msg->success("Le projet a bien été ajouté !", $this->getUrl(true));
                } else {
                    $this->msg->error("L'article n'a pas pu être ajouté.", $this->getUrl(true));
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }

    /**
     * MODIFIER UN PROJET
     *
     * - récupère identifiant, titre, contenu, catégorie et image
     * - vérifie que le token CSRF est bon
     * - upload l'image dans un dossier propre au projet
     * - met à jour le projet
     * - met à jour la catégorie liée au projet
     */
    public function updateProject() {
        $session_token = $_SESSION['update_project_token'];
        $token = $_POST['update_project_token'];
        $id = htmlspecialchars($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $content =  html_entity_decode($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $id = htmlspecialchars($_POST['id']);
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $imageId = $this->uploadService->uploadProject($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image, $title);
                $data = [
                    'title'         => $title,
                    'link'          => $link,
                    'content'       => $content,
                    'img_id'        => $imageId,
                    'published'     => 1,
                    'id'            => $id,
                    ];

                if ($this->projectModel->updateProject($data)) {
                    $this->categoryModel->updateCategoryToProject($category, $id);
                    $this->msg->success("Le projet a bien été modifié !");
                    header('Location: ' . '?c=adminDashboard&t=projects&page=1');
                    exit;
                } else {
                    $this->msg->error("L'article n'a pas pu être modifié.");
                    header('Location: ' . '?c=adminDashboard&page=1');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }

    /**
     * SUPPRIMER UN PROJET
     *
     * - récupère l'identifiant du projet
     * - vérifie que le token CSRF est bon
     * - supprime le projet de la base de données
     */
    public function deleteProject() {
        $session_token = $_SESSION['delete_project_token'];
        $token = $_POST['delete_project_token'];
        $id = $_POST['projectId'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($id) && $project = $this->projectModel->getProjectById($id)) {
                    $imageId = $project['img_id'];

                    if ($this->projectModel->deleteProject($project['id'])) {
                        //if ($imageId != 14) {
                        //    $this->imageModel->deleteImage($imageId);
                        //}
                        //$this->categoryModel->minusNumberPosts($category);
                        $this->msg->success("Le projet a bien été supprimé");
                        header('Location: ?c=adminDashboard&t=projects&page=1');
                        exit;
                    } else {
                        $this->msg->error("Le projet n'a pas pu être supprimé");
                        header('Location: ?c=adminDashboard&t=projects&page=1');
                        exit;
                    }
                } else {
                    header('Location: ?c=adminDashboard&page=1');
                    exit;
                }
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }

    /**
     * PUBLIER OU DEPUBLIER UN PROJET
     */
    public function togglePublished() {
        $published = $_GET['g'];
        $id = $_GET['id'];
        if ($published) {
            $this->projectModel->unPublish($id);
            $this->msg->success("Le projet a bien été dépublié !", $this->getUrl(true));
        } else {
            $this->projectModel->publish($id);
            $this->msg->success("Le projet a bien été publié !", $this->getUrl(true));
        }
    }
}
