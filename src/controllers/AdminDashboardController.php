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
    }
    /*
     * AFFICHER LES ARTICLES
     */
    public function index() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 8;
        $number_of_posts = $this->blogModel->getNumber();
        $number_of_pages = ceil($number_of_posts/$results_per_page);

        $posts = $this->paginationService->paginate($currentPage, $number_of_pages, $results_per_page);
        //$posts = $this->blogModel->getAllPostsWithUsers();
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        $tags_per_post = $this->blogModel->getTagsPerPost($id);
        echo $this->twig->render('admin/dashboard/posts/index.html.twig', [
            'message'       => $this->msg,
            'posts'         => $posts,
            'categories'    => $categories,
            'tags'          => $tags,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_posts,
            '__DIR__'       => '?c=adminDashboard',
            'tags_per_post' => $tags_per_post,
            'id'            => $id,
        ]);
    }
    /*
     * AFFICHER LES MEMBRES
     */
    public function users() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 20;
        $number_of_users = $this->userModel->getNumberOfUsers();
        $number_of_pages = ceil($number_of_users/$results_per_page);

        $users = $this->paginationService->paginateUser($currentPage, $number_of_pages, $results_per_page);

        echo $this->twig->render('admin/dashboard/users/users.html.twig', [
            'message'   => $this->msg,
            'users'     => $users,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_users,
            '__DIR__'       => '?c=adminDashboard&t=users',
        ]);
    }
    /*
     * AFFICHER LES CATEGORIES
     */
    public function categories() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 20;
        $number_of_categories = $this->categoryModel->getNumberOfCategories();
        $number_of_pages = ceil($number_of_categories/$results_per_page);

        $categories = $this->paginationService->paginateCategoryAdmin($currentPage, $number_of_pages, $results_per_page);
        //$categories = $this->categoryModel->getAllCategories();
        echo $this->twig->render('admin/dashboard/categories/categories.html.twig', [
            'message'       => $this->msg,
            'categories'    => $categories,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_categories,
            '__DIR__'       => '?c=adminDashboard&t=categories',
        ]);
    }
    /*
     * AFFICHER LES COMMENTAIRES
     */
    public function comments() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 10;
        $number_of_comments = $this->commentModel->getNumberComments();
        $number_of_pages = ceil($number_of_comments/$results_per_page);
        $comments = $this->paginationService->paginateCommentAdmin($currentPage, $number_of_pages, $results_per_page);
        //$comments = $this->commentModel->getAllComments();
        echo $this->twig->render('admin/dashboard/comments/comments.html.twig', [
            'message'       => $this->msg,
            'comments'      => $comments,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_comments,
            '__DIR__'       => '?c=adminDashboard&t=comments',
        ]);
    }
    /*
     * AFFICHER LES TAGS
     */
    public function tags() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 10;
        $number_of_tags = $this->tagModel->getNumberOfTags();
        $number_of_pages = ceil($number_of_tags/$results_per_page);
        $tags = $this->paginationService->paginateTagAdmin($currentPage, $number_of_pages, $results_per_page);
        //$tags = $this->tagModel->getAllTags();
        echo $this->twig->render('admin/dashboard/tags/tags.html.twig', [
            'message'       => $this->msg,
            'tags'          => $tags,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_tags,
            '__DIR__'       => '?c=adminDashboard&t=tags',
        ]);
    }
    /*
     * AFFICHER LES PROJETS
     */
    public function projects() {
        $currentPage = intval($_GET['page']);
        $results_per_page = 8;
        $number_of_projects = $this->projectModel->getNumberOfProjects();
        $number_of_pages = ceil($number_of_projects/$results_per_page);

        $projects = $this->paginationService->paginateProject($currentPage, $number_of_projects, $results_per_page);
        echo $this->twig->render('admin/dashboard/projects/projects.html.twig', [
            'message'       => $this->msg,
            'projects'      => $projects,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_projects,
            '__DIR__'       => '?c=adminDashboard&t=projects',
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
