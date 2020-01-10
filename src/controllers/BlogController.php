<?php

namespace Controllers;

use Models\Blog;
use Service\PaginationService;
use Models\Config;
use Models\Tag;
use Models\Category;
use Models\Link;
use Models\Comment;

/**
 * CLASSE GERANT LE BLOG
 */
class BlogController extends Controller
{
    protected $blogModel;
    protected $paginationService;
    protected $configModel;
    protected $categoryModel;
    protected $tagModel;
    protected $linkModel;
    protected $commentModel;

    public function __construct() {
        parent::__construct();
        $this->blogModel = new Blog;
        $this->paginationService = new PaginationService;
        $this->configModel = new Config;
        $this->categoryModel = new Category;
        $this->tagModel = new Tag;
        $this->linkModel = new Link;
        $this->commentModel = new Comment;
    }
    /**
     * AFFICHER LA PAGE PRINCIPALE
     *
     * - récupère le type d'affichage désiré
     * - récupération des articles et pagination
     * - récupération des 3 articles les plus commentés
     * - récupération des catégories
     * - récupération des tags
     * - récupération des liens
     * - récupération des sous-liens
     * - récupération des tags par article
     */
    public function index() {
        $view = $_GET['v'];
        $currentPage = intval($_GET['page']);
        $results_per_page = $this->configModel->getConfig()['ppp'];
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        $posts = $this->paginationService->paginate($currentPage, $number_of_pages, $results_per_page);
        $populars = $this->blogModel->getMostSeens();
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        $links = $this->linkModel->getAllLinks();
        $sublinks = $this->linkModel->getAllSublinks();
        $id = "";
        $tags_per_post = $this->blogModel->getTagsPerPost($id);

        $data = [
            'posts'         => $posts,
            'numberOfPages' => $number_of_pages,
            'number'        => $number_of_posts,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
            'populars'      => $populars,
            'links'         => $links,
            'sublinks'      => $sublinks,
            'tags_per_post' => $tags_per_post,
            ];

        if ($view == "view1") {
            $data['__DIR__'] = '?c=blog&v=view1';
            echo $this->twig->render('front/blog/index.html.twig', $data);
        } elseif ($view == "view2") {
            $data['__DIR__'] = '?c=blog&v=view2';
            echo $this->twig->render('front/blog/index2.html.twig', $data);
        } elseif ($view == 'view3') {
            $data['__DIR__'] = '?c=blog&v=view3';
            echo $this->twig->render('front/blog/index3.html.twig', $data);
        } else {
            $this->redirect404();
        }
    }
    /*
     * AFFICHER UN ARTICLE EN PARTICULIER
     *
     * - met en place un token CSRF pour l'ajout d'un commentaire
     * - récupération de l'article avec son identifiant
     * - récupération des catégories, tags, populaires, liens, sous-liens et tags par article
     * - récupération des commentaires liés à l'article
     */
    public function post() {

        $token = bin2hex(openssl_random_pseudo_bytes(6));
        $_SESSION['add_comment_token'] = $token;
        $_SESSION['update_comment_token'] = $token;
        $_SESSION['delete_comment_token'] = $token;

        // if (!isset($_SESSION['add_comment_token']) || ($_SESSION['add_comment_token'] == NULL)) {
        //    $_SESSION['add_comment_token'] = bin2hex(openssl_random_pseudo_bytes(6));
        // }

        if (isset($_GET['id']) && $post = $this->blogModel->getPostById($_GET['id'])) {
            if ($post['published'] || $this->isAdmin()) {
                $post['content'] = htmlspecialchars_decode($post['content'], ENT_HTML5);
                $categories = $this->categoryModel->getAllCategories();
                $tags = $this->tagModel->getAllTags();
                $populars = $this->blogModel->getMostSeens();
                $links = $this->linkModel->getAllLinks();
                $sublinks = $this->linkModel->getAllSublinks();
                $id = '';
                $tags_per_post = $this->blogModel->getTagsPerPost($id);
                if ($this->isLogged()) {
                    $comments = $this->commentModel->getVerifiedCommentsByPostId($post['id']);
                } else {
                    $comments = null;
                }
                echo $this->twig->render('front/blog/_partials/post/index.html.twig', [
                    'post'              => $post,
                    'comments'          => $comments,
                    'message'           => $this->msg,
                    'maxLength'         => $this->configModel->getConfig()['characters'],
                    'categories'        => $categories,
                    'tags'              => $tags,
                    'populars'          => $populars,
                    //'subcomments'       => $subcomments,
                    'links'             => $links,
                    'sublinks'          => $sublinks,
                    'tags_per_post'     => $tags_per_post,
                    'add_comment_token' => $token,
                    'update_comment_token' => $token,
                    'delete_comment_token' => $token,
                ]);
            } else {
                $this->redirect404();
            }
        } else {
            $this->redirect404();
        }
    }
    /**
     * RECUPERER LES ARTICLES PAR CATEGORIE
     *
     * - récupération des articles populaires, catégories, tags, liens, sous-liens et tags par article
     * - récupération des articles par catégorie et pagination
     */
    public function getPostsByCategory($category) {
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $currentPage = intval($_GET['page']);
        if (isset($category)) {
            $results_per_page = $this->configModel->getConfig()['ppp'];
            $populars = $this->blogModel->getMostSeens();
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();
            $links = $this->linkModel->getAllLinks();
            $sublinks = $this->linkModel->getAllSublinks();
            $url = $this->getUrl();
            $id= "";
            $tags_per_post = $this->blogModel->getTagsPerPost($id);
            $number = $this->blogModel->countSearchByCategoryRequest($category);
            $number = (int)$number;
            $number_of_pages = ceil($number/$results_per_page);
            if ($number_of_pages == 0) {
                $number_of_pages += 1;
            }
            $posts = $this->paginationService->paginateCategory($category, $currentPage, $number_of_pages, $results_per_page);
            echo $this->twig->render('front/blog/_partials/category/index.html.twig', [
                'posts'         => $posts,
                'number'        => $number,
                'numberOfPages' => $number_of_pages,
                'categories'    => $categories,
                'tags'          => $tags,
                'url'           => $url,
                'populars'      => $populars,
                'category'      => $category,
                'links'         => $links,
                'sublinks'      => $sublinks,
                'tags_per_post' => $tags_per_post,
                '__DIR__'       => '?c=blog&t=getPostsByCategory&category=PHP',
            ]);
        }
    }
    /**
     * RECUPERER LES ARTICLES PAR TAG
     *
     * - récupération des articles populaires, catégories, tags, liens, sous-liens et tags par article
     * - récupération des articles par tag et pagination
     */
    public function getPostsByTag($tag) {
        $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
        $currentPage = intval($_GET['page']);
        if (isset($tag)) {
            $results_per_page = $this->configModel->getConfig()['ppp'];
            $populars = $this->blogModel->getMostSeens();
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();
            $links = $this->linkModel->getAllLinks();
            $sublinks = $this->linkModel->getAllSublinks();
            $url = $this->getUrl();
            $posts = $this->blogModel->searchByTag($tag);
            $number = $this->blogModel->countSearchByTagRequest($tag);
            $number = (int)$number;
            $number_of_pages = ceil($number/$results_per_page);
            if ($number_of_pages == 0) {
                $number_of_pages += 1;
            }
            $id = "";
            $tags_per_post = $this->blogModel->getTagsPerPost($id);

            $posts = $this->paginationService->paginateTag($tag, $currentPage, $number_of_pages, $results_per_page);
            echo $this->twig->render('front/blog/_partials/tag/index.html.twig', [
                'posts'         => $posts,
                'number'        => $number,
                'numberOfPages' => $number_of_pages,
                'categories'    => $categories,
                'tags'          => $tags,
                'url'           => $url,
                'populars'      => $populars,
                'tag'           => $tag,
                'links'         => $links,
                'sublinks'      => $sublinks,
                'tags_per_post' => $tags_per_post,
                '__DIR__'       => '?c=blog&t=getPostsByTag&tag=PHP',
            ]);
        }
    }
}
