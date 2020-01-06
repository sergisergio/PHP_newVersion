<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\Image;
use Models\Tag;
use Models\Project;
use Service\UploadService;

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
    }

    /**
     * AJOUTER UN PROJET
     */
    public function addProject() {
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
                // header('Location: ' . '?c=adminDashboard&page=1');
                // exit;

            } else {
                //$this->msg->error("L'article n'a pas pu être ajouté.", $this->getUrl());
                header('Location: ' . '?c=adminDashboard&page=1');
                exit;
            }
        } else {
            header('Location: ' . '?c=adminDashboard&page=1');
            exit;
        }
    }

    /**
     * MODIFIER UN PROJET
     */
    public function updateProject() {
        $id = htmlspecialchars($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $content =  html_entity_decode($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $id = htmlspecialchars($_POST['id']);
        $tag = $_POST['tag'];
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

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
        } else {
            header('Location: ' . '?c=adminDashboard&page=1');
            exit;
        }
    }

    /**
     * SUPPRIMER UN PROJET
     */
    public function deleteProject() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['projectId'];
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
                $this->msg->error("Le projet n'existe pas", $this->getUrl(true));
                //header('Location: ?c=adminDashboard&t=projects&page=1');
                //exit;
            }
        } else {
            header('Location: ?c=adminDashboard&page=1');
            exit;
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
            //header('Location: ?c=adminDashboard&t=projects');
            //exit;
        } else {
            $this->projectModel->publish($id);
            $this->msg->success("Le projet a bien été publié !", $this->getUrl(true));
            //header('Location: ?c=adminDashboard&t=projects');
            //exit;
        }
    }
}
