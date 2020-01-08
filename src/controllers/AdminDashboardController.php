<?php

namespace Controllers;

use Models\Blog;
use Models\Category;
use Models\User;
use Models\Comment;
use Models\Tag;
use Models\Project;
use Models\Config;
use Service\PaginationService;
use Service\SecurityService;

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
    protected $paginationService;
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
        if ($this->getUri() == '/index.php?c=adminDashboard') {
            header('Location: ?c=adminDashboard&page=1');
            exit;
        }
        if ($this->getUri() == '/index.php?c=adminDashboard&t=comments') {
            header('Location: ?c=adminDashboard&t=comments&page=1');
            exit;
        }
        if ($this->getUri() == '/index.php?c=adminDashboard&t=users') {
            header('Location: ?c=adminDashboard&t=users&page=1');
            exit;
        }
        if ($this->getUri() == '/index.php?c=adminDashboard&t=categories') {
            header('Location: ?c=adminDashboard&t=categories&page=1');
            exit;
        }
        if ($this->getUri() == '/index.php?c=adminDashboard&t=tags') {
            header('Location: ?c=adminDashboard&t=tags&page=1');
            exit;
        }
        if ($this->getUri() == '/index.php?c=adminDashboard&t=projects') {
            header('Location: ?c=adminDashboard&t=projects&page=1');
            exit;
        }
        $this->blogModel = new Blog;
        $this->categoryModel = new Category;
        $this->userModel = new User;
        $this->commentModel = new Comment;
        $this->tagModel = new Tag;
        $this->projectModel = new Project;
        $this->configModel = new Config;
        $this->paginationService = new PaginationService;
        $this->securityService = new SecurityService;
    }
    /*
     * AFFICHER LES ARTICLES
     */
    public function index() {

        if (!isset($_SESSION['add_post_token']) || ($_SESSION['add_post_token'] == NULL)) {
           $_SESSION['add_post_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['update_post_token']) || ($_SESSION['update_post_token'] == NULL)) {
           $_SESSION['update_post_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['delete_post_token']) || ($_SESSION['delete_post_token'] == NULL)) {
           $_SESSION['delete_post_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }

        $currentPage = intval($_GET['page']);
        $results_per_page = 8;
        $number_of_posts = $this->blogModel->getNumber();
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }

        $posts = $this->paginationService->paginate($currentPage, $number_of_pages, $results_per_page);
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        $tags_per_post = $this->blogModel->getTagsPerPost($id);
        echo $this->twig->render('admin/dashboard/posts/index.html.twig', [
            'message'            => $this->msg,
            'posts'              => $posts,
            'categories'         => $categories,
            'tags'               => $tags,
            'numberOfPages'      => $number_of_pages,
            'number'             => $number_of_posts,
            '__DIR__'            => '?c=adminDashboard',
            'tags_per_post'      => $tags_per_post,
            'id'                 => $id,
            'add_post_token'     => $_SESSION['add_post_token'],
            'update_post_token'  => $_SESSION['update_post_token'],
            'delete_post_token'  => $_SESSION['delete_post_token'],
        ]);
    }
    /*
     * AFFICHER LES MEMBRES
     */
    public function users() {
        if (!isset($_SESSION['update_user_token']) || ($_SESSION['update_user_token'] == NULL)) {
           $_SESSION['update_user_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['delete_user_token']) || ($_SESSION['delete_user_token'] == NULL)) {
           $_SESSION['delete_user_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }

        $currentPage = intval($_GET['page']);
        $results_per_page = 20;
        $number_of_users = $this->userModel->getNumberOfUsers();
        $number_of_pages = ceil($number_of_users/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }

        $users = $this->paginationService->paginateUser($currentPage, $number_of_pages, $results_per_page);

        echo $this->twig->render('admin/dashboard/users/users.html.twig', [
            'message'            => $this->msg,
            'users'              => $users,
            'numberOfPages'      => $number_of_pages,
            'number'             => $number_of_users,
            '__DIR__'            => '?c=adminDashboard&t=users',
            'update_user_token'  => $_SESSION['update_user_token'],
            'delete_user_token'  => $_SESSION['delete_user_token'],
        ]);
    }
    /*
     * AFFICHER LES CATEGORIES
     */
    public function categories() {
        if (!isset($_SESSION['add_category_token']) || ($_SESSION['add_category_token'] == NULL)) {
           $_SESSION['add_category_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['update_category_token']) || ($_SESSION['update_category_token'] == NULL)) {
           $_SESSION['update_category_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['delete_category_token']) || ($_SESSION['delete_category_token'] == NULL)) {
           $_SESSION['delete_category_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        $currentPage = intval($_GET['page']);
        $results_per_page = 20;
        $number_of_categories = $this->categoryModel->getNumberOfCategories();
        $number_of_pages = ceil($number_of_categories/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }

        $categories = $this->paginationService->paginateCategoryAdmin($currentPage, $number_of_pages, $results_per_page);

        echo $this->twig->render('admin/dashboard/categories/categories.html.twig', [
            'message'                => $this->msg,
            'categories'             => $categories,
            'numberOfPages'          => $number_of_pages,
            'number'                 => $number_of_categories,
            '__DIR__'                => '?c=adminDashboard&t=categories',
            'add_category_token'     => $_SESSION['add_category_token'],
            'update_category_token'  => $_SESSION['update_category_token'],
            'delete_category_token'  => $_SESSION['delete_category_token'],
        ]);
    }
    /*
     * AFFICHER LES COMMENTAIRES
     */
    public function comments() {
        if (!isset($_SESSION['delete_comment_token']) || ($_SESSION['delete_comment_token'] == NULL)) {
           $_SESSION['delete_comment_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        $_SESSION['delete_comment_token'] = $delete_comment_token;
        $currentPage = intval($_GET['page']);
        $results_per_page = 10;
        $number_of_comments = $this->commentModel->getNumberComments();
        $number_of_pages = ceil($number_of_comments/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }
        $comments = $this->paginationService->paginateCommentAdmin($currentPage, $number_of_pages, $results_per_page);

        echo $this->twig->render('admin/dashboard/comments/comments.html.twig', [
            'message'               => $this->msg,
            'comments'              => $comments,
            'numberOfPages'         => $number_of_pages,
            'number'                => $number_of_comments,
            '__DIR__'               => '?c=adminDashboard&t=comments',
            'delete_comment_token'  => $_SESSION['delete_comment_token'],
        ]);
    }
    /*
     * AFFICHER LES TAGS
     */
    public function tags() {
        if (!isset($_SESSION['add_tag_token']) || ($_SESSION['add_tag_token'] == NULL)) {
           $_SESSION['add_tag_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['update_tag_token']) || ($_SESSION['update_tag_token'] == NULL)) {
           $_SESSION['update_tag_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['delete_tag_token']) || ($_SESSION['delete_tag_token'] == NULL)) {
           $_SESSION['delete_tag_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }

        $currentPage = intval($_GET['page']);
        $results_per_page = 10;
        $number_of_tags = $this->tagModel->getNumberOfTags();
        $number_of_pages = ceil($number_of_tags/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }
        $tags = $this->paginationService->paginateTagAdmin($currentPage, $number_of_pages, $results_per_page);

        echo $this->twig->render('admin/dashboard/tags/tags.html.twig', [
            'message'           => $this->msg,
            'tags'              => $tags,
            'numberOfPages'     => $number_of_pages,
            'number'            => $number_of_tags,
            '__DIR__'           => '?c=adminDashboard&t=tags',
            'add_tag_token'     => $_SESSION['add_tag_token'],
            'update_tag_token'  => $_SESSION['update_tag_token'],
            'delete_tag_token'  => $_SESSION['delete_tag_token'],
        ]);
    }
    /*
     * AFFICHER LES PROJETS
     */
    public function projects() {
        if (!isset($_SESSION['add_project_token']) || ($_SESSION['add_project_token'] == NULL)) {
           $_SESSION['add_project_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['update_project_token']) || ($_SESSION['update_project_token'] == NULL)) {
           $_SESSION['update_project_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }
        if (!isset($_SESSION['delete_project_token']) || ($_SESSION['delete_project_token'] == NULL)) {
           $_SESSION['delete_project_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }

        $currentPage = intval($_GET['page']);
        $results_per_page = 8;
        $number_of_projects = $this->projectModel->getNumberOfProjects();
        $number_of_pages = ceil($number_of_projects/$results_per_page);
        if ($number_of_pages == 0) {
            $number_of_pages += 1;
        }
        $categories = $this->categoryModel->getAllCategories();
        $projects = $this->paginationService->paginateProject($currentPage, $number_of_projects, $results_per_page);
        echo $this->twig->render('admin/dashboard/projects/projects.html.twig', [
            'message'               => $this->msg,
            'projects'              => $projects,
            'numberOfPages'         => $number_of_pages,
            'number'                => $number_of_projects,
            '__DIR__'               => '?c=adminDashboard&t=projects',
            'categories'            => $categories,
            'add_project_token'     => $_SESSION['add_project_token'],
            'update_project_token'  => $_SESSION['update_project_token'],
            'delete_project_token'  => $_SESSION['delete_project_token'],
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
