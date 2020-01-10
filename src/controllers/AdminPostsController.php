<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\Image;
use Models\Tag;
use Service\UploadService;
use Service\SecurityService;

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
    protected $tagModel;
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
        $this->uploadService = new UploadService;
        $this->securityService = new SecurityService;
    }
    /**
     * AJOUTER UN ARTICLE
     *
     * - récupère titre, contenu, image, tag, auteur via formulaire
     * - vérifie que le token CSRF est bon
     * - upload de l'image dans un dossier propre au projet
     * - ajout de l'article en base de données
     * - ajout d'une catégorie liée à l'article en base de données
     * - définit le nombre d'articles reliés à une catégorie
     * - lie les tags à l'article
     */
    public function addPost() {
        $session_token = $_SESSION['add_post_token'];
        $token = $_POST['add_post_token'];
        $title = htmlspecialchars($_POST['title']);
        $content =  html_entity_decode($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $tag = $_POST['tag'];
        $user_id = $_SESSION['admin']['id'];
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if (isset($title) && isset($content)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $imageId = $this->uploadService->uploadPost($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image, $title);
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
                        foreach ($tag as $singleTag) {
                            $this->tagModel->linkTagsToPost($singleTag, $last_id);
                        }
                        $this->msg->success("L'article a bien été ajouté !", $this->getUrl());
                        header('Location: ' . '?c=adminDashboard&page=1');
                        exit;

                    } else {
                        $this->msg->error("L'article n'a pas pu être ajouté.", $this->getUrl());
                        header('Location: ' . '?c=adminDashboard&page=1');
                        exit;
                    }
                } else {
                    header('Location: ' . '?c=adminDashboard&page=1');
                    exit;
                }
            } else {
             $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * MODIFIER UN ARTICLE
     *
     * - récupération du titre, contenu, catégorie, image, identifiant et auteur
     * - vérifie que le token CSRF est bon
     * - met à jour l'image
     * - met à jour l'article
     * - met à jour la catégorie liée à l'article
     * - met à jour les tags liés au post
     */
    public function updatePost() {
        $session_token = $_SESSION['update_post_token'];
        $token = $_POST['update_post_token'];
        $title = htmlspecialchars($_POST['title']);
        $content =  html_entity_decode($_POST['content']);
        $category = htmlspecialchars($_POST['category']);
        $image = htmlspecialchars($_FILES['file_extension']['name']);
        $id = htmlspecialchars($_POST['id']);
        $tag = $_POST['tag'];
        $user_id = $_SESSION['admin']['id'];
        $file_extension = $_FILES['file_extension'];
        $file_extension_error = $_FILES['file_extension']['error'];
        $file_extension_size = $_FILES['file_extension']['size'];
        $file_extension_tmp = $_FILES['file_extension']['tmp_name'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $imageId = $this->uploadService->uploadPost($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image, $title);
                $data = [
                        'title'         => $title,
                        'content'       => $content,
                        'user_id'       => $user_id,
                        'img_id'        => $imageId,
                        'published'     => 1,
                        'id'            => $id,
                    ];

                if ($this->blogModel->updatePost($data)) {
                    $this->categoryModel->deleteCategoryToPost($id);
                    $this->categoryModel->addCategoryToPost($category, $id);
                    $this->tagModel->deleteTagsToPost($id);
                    foreach ($tag as $singleTag) {
                        $this->tagModel->linkTagsToPost($singleTag, $id);
                    }
                    $this->msg->success("L'article a bien été modifié !");
                    header('Location: ' . '?c=adminDashboard&page=1');
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
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }
    /**
     * SUPPRIMER UN ARTICLE
     *
     * - récupère l'identifiant de l'article
     * - vérifie que le token CSRF est bon
     * - supprime l'article ainsi que son image
     */
    public function deletePost() {
        $session_token = $_SESSION['delete_post_token'];
        $delete_post_token = $_POST['delete_post_token'];
        $postId = $_POST['postId'];

        if ($this->securityService->checkCsrf($token, $session_token)) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($postId) && $post = $this->blogModel->getPostById($_POST['postId'])) {
                    $category = $post['category'];
                    $imageId = $post['img_id'];
                    if ($this->blogModel->deletePost($post['id'])) {
                        if ($imageId != 14) {
                            $this->imageModel->deleteImage($imageId);
                        }
                        $this->categoryModel->minusNumberPosts($category);
                        $this->msg->success("L'article a bien été supprimé");
                        header('Location: ?c=adminDashboard&page=1');
                        exit;
                    } else {
                        $this->msg->error("L'article n'a pas pu être supprimé");
                        header('Location: ?c=adminDashboard&page=1');
                        exit;
                    }
                } else {
                    $this->msg->error("L'article n'existe pas", $this->getUrl());
                    header('Location: ?c=adminDashboard&page=1');
                    exit;
                }
            } else {
                header('Location: ?c=adminDashboard&page=1');
                exit;
            }
        } else {
            $this->msg->error("Une erreur est survenue !", $this->getUrl(true));
        }
    }

    /**
     * PUBLIER OU DEPUBLIER UN ARTICLE
     *
     * - récupère l'identifiant et le statut de l'article
     * - publie l'article si celui-ci est dépublié
     * - dépublie l'article si celui-ci est publié
     */
    public function togglePublished() {
        $published = $_GET['g'];
        $id = $_GET['id'];
        if ($published) {
            $this->blogModel->unPublish($id);
            $this->msg->success("L'article a bien été dépublié !");
            header('Location: ?c=adminDashboard&page=1');
            exit;
        } else {
            $this->blogModel->publish($id);
            $this->msg->success("L'article a bien été publié !");
            header('Location: ?c=adminDashboard&page=1');
            exit;
        }
    }
}
