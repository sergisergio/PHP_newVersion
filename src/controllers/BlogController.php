<?php

namespace Controllers;

/**
 * CLASSE GERANT LE BLOG
 */
class BlogController extends Controller
{
    /**
     * AFFICHER LA PAGE PRINCIPALE
     */
    public function index() {
        $view = $_GET['v'];
        $currentPage = intval($_GET['page']);
        $results_per_page = $this->configModel->getConfig()['ppp'];
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        //$this->debugService->display_debug('$posts', $posts, true);

        $posts = $this->paginationService->paginate($currentPage, $number_of_pages, $results_per_page);

        //var_dump($currentPage);die();
        $populars = $this->blogModel->getMostSeens();
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        $links = $this->linkModel->getAllLinks();
        $sublinks = $this->linkModel->getAllSublinks();

        $data = [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
            'populars'      => $populars,
            'links'         => $links,
            'sublinks'      => $sublinks,
            ];

        if ($view == "view1") {
            echo $this->twig->render('front/blog/index.html.twig', $data);
        } elseif ($view == "view2") {
            echo $this->twig->render('front/blog/index2.html.twig', $data);
        } elseif ($view == 'view3') {
            echo $this->twig->render('front/blog/index3.html.twig', $data);
        } else {
            $this->redirect404();
        }
    }
    /*
     * AFFICHER UN ARTICLE EN PARTICULIER
     */
    public function post() {
        if (isset($_GET['id']) && $post = $this->blogModel->getPostById($_GET['id'])) {
            if ($post['published'] || $this->isAdmin()) {
                $post['content'] = htmlspecialchars_decode($post['content'], ENT_HTML5);
                $categories = $this->categoryModel->getAllCategories();
                $tags = $this->tagModel->getAllTags();
                $populars = $this->blogModel->getMostSeens();
                $links = $this->linkModel->getAllLinks();
                $sublinks = $this->linkModel->getAllSublinks();
                if ($this->isLogged()) {
                    $comments = $this->commentModel->getVerifiedCommentsByPostId($post['id']);
                } else {
                    $comments = null;
                }
                echo $this->twig->render('front/blog/_partials/post/index.html.twig', [
                    'post'        => $post,
                    'comments'    => $comments,
                    'message'     => $this->msg,
                    'maxLength'   => $this->configModel->getConfig()['characters'],
                    'categories'  => $categories,
                    'tags'        => $tags,
                    'populars'    => $populars,
                    'subcomments' => $subcomments,
                    'links'       => $links,
                    'sublinks'    => $sublinks,
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
            //$posts = $this->blogModel->searchByCategory($category);
            $number = $this->blogModel->countSearchByCategoryRequest($category);
            $number = (int)$number;
            $number_of_pages = ceil($number/$results_per_page);
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
            ]);
        }
    }
    /**
     * RECUPERER LES ARTICLES PAR TAG
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
            ]);
        }
    }
}
