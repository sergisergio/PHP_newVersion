<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\Image;
use Service\UploadService;

/**
 * classe AdminPostsController
 *
 * Cette classe gère les articles
 */
class AdminPostsController extends Controller
{
    protected $categoryModel;
    protected $blogModel;
    protected $imageModel;
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
        $this->uploadService = new UploadService;
    }
    /**
     * AJOUTER UN ARTICLE
     */
    public function addPost() {
        $title = htmlspecialchars($_POST['title']);
        $content =  htmlspecialchars($_POST['content'], ENT_HTML5);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $user_id = $_SESSION['admin']['id'];
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageId = $this->uploadService->upload($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image);
            $data = [
                    'title'         => $title,
                    'content'       => $content,
                    'user_id'       => $user_id,
                    'img_id'        => $imageId,
                    'published'     => 1,
                    'numberComments' => 0
                ];

            if ($this->blogModel->setPost($data)) {
                $last_id = $this->blogModel->getLastId();
                $this->categoryModel->addCategoryToPost($category, $last_id);
                $this->categoryModel->plusNumberPosts($category);
                $this->msg->success("L'article a bien été ajouté !", $this->getUrl());
            } else {
                $this->msg->error("L'article n'a pas pu être ajouté.", $this->getUrl());
            }
        } else {
            header('Location: ' . '?c=adminDashboard');
            exit;
        }
    }
    /**
     * SUPPRIMER UN ARTICLE
     */
    public function deletePost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postId = $_POST['postId'];
            if (isset($postId) && $post = $this->blogModel->getPostById($_POST['postId'])) {
                $category = $post['category'];
                $imageId = $post['img_id'];

                if ($this->blogModel->deletePost($post['id'])) {
                    if ($imageId != 14) {
                        $this->imageModel->deleteImage($imageId);
                    }
                    $this->categoryModel->minusNumberPosts($category);
                    $this->msg->success("L'article a bien été supprimé", $this->getUrl());
                } else {
                    $this->msg->error("L'article n'a pas pu être supprimé", $this->getUrl());
                }
            } else {
                $this->msg->error("L'article n'existe pas", $this->getUrl());
            }
        } else {
            header('Location: ?c=adminDashboard');
            exit;
        }
    }
}
