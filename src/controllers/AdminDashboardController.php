<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\User;
use Models\Comment;
use Models\Tag;
use Models\Project;
use Models\Config;

/**
 * class AdminDashboardController
 *
 * CLASSE GERANT LA PARTIE ADMIN
 */
class AdminDashboardController extends AdminController
{
    protected $categoryModel;
    protected $blogModel;
    protected $userModel;
    protected $commentModel;
    protected $tagModel;
    protected $projectModel;
    protected $configModel;
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
        $this->blogModel = new Blog;
        $this->categoryModel = new Category;
        $this->userModel = new User;
        $this->commentModel = new Comment;
        $this->tagModel = new Tag;
        $this->projectModel = new Project;
        $this->configModel = new Config;
    }
    /*
     * AFFICHER LES ARTICLES
     */
    public function index() {
        $posts = $this->blogModel->getAllPostsWithUsers();
        $categories = $this->categoryModel->getAllCategories();
        echo $this->twig->render('admin/dashboard/posts/index.html.twig', [
            'message'   => $this->msg,
            'posts'     => $posts,
            'categories'     => $categories
        ]);
    }
    /*
     * AFFICHER LES MEMBRES
     */
    public function users() {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('admin/dashboard/users/users.html.twig', [
            'message'   => $this->msg,
            'users'     => $users
        ]);
    }
    /*
     * AFFICHER LES CATEGORIES
     */
    public function categories() {
        $categories = $this->categoryModel->getAllCategories();
        echo $this->twig->render('admin/dashboard/categories/categories.html.twig', [
            'message'   => $this->msg,
            'categories'     => $categories
        ]);
    }
    /*
     * AFFICHER LES COMMENTAIRES
     */
    public function comments() {
        $comments = $this->commentModel->getAllComments();
        echo $this->twig->render('admin/dashboard/comments/comments.html.twig', [
            'message'   => $this->msg,
            'comments'     => $comments
        ]);
    }
    /*
     * AFFICHER LES TAGS
     */
    public function tags() {
        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('admin/dashboard/tags/tags.html.twig', [
            'message'   => $this->msg,
            'tags'     => $tags
        ]);
    }
    /*
     * AFFICHER LES PROJETS
     */
    public function projects() {
        $projects = $this->projectModel->getAllProjects();
        echo $this->twig->render('admin/dashboard/projects/projects.html.twig', [
            'message'   => $this->msg,
            'projects'  => $projects
        ]);
    }
    /**
     * METTRE A JOUR LA CONFIGURATION
     */
    public function editConfig() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit_config'])) {
            if (empty($_POST['ppp']) || empty($_POST['cpc'])) {
                $this->msg->error("Tous les champs n'ont pas été remplis", $this->getUrl(true));
            } else {
                $ppp = $_POST['ppp'];
                $cpc = $_POST['cpc'];
                if ($this->model->updateConfig($ppp, $cpc)) {
                    $this->msg->success("La configuration a bien été modifié", $this->getUrl(true));
                } else {
                    $this->msg->error("Une erreur s'est produite", $this->getUrl(true));
                }
            }
        } else {
            $this->redirect404();
        }
    }
}
