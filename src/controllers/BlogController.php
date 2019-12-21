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
    public function view1() {
        // define how many results you want per page
        $results_per_page = $this->configModel->getConfig()['ppp'];
        // find out the number of results stored in database
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        // determine number of total pages available
        $number_of_pages = ceil($number_of_posts/$results_per_page);

        $url = $this->getUrl();
        //var_dump($url);die();

        // Minimum 1 page
        if ($number_of_pages == 0)
            $number_of_pages = 1;
        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        // check if the page exist
        if ($page > $number_of_pages || $page == 0) {
            $this->redirect404();
        }
        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page-1)*$results_per_page;
        // retrieve selected results from database and display them on page
        $posts = $this->blogModel->getPostsPagination($this_page_first_result, $results_per_page);

        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        //$mostSeens = $this->blogModel->getMostSeenPosts();
        echo $this->twig->render('front/blog/index.html.twig', [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
            //'populars'      => $mostSeens,
        ]);
    }
    /*
     * AFFICHER UN ARTICLE EN PARTICULIER
     */
    public function post() {
        if (isset($_GET['id']) && $post = $this->blogModel->getPostById($_GET['id'])) {
            if ($post['published'] || $this->isAdmin()) {
                $post['content'] = htmlspecialchars_decode($post['content'], ENT_HTML5);
                if ($this->isLogged()) {
                    $comments = $this->commentModel->getVerifiedCommentsByPostId($post['id']);
                    $categories = $this->categoryModel->getAllCategories();
                    $tags = $this->tagModel->getAllTags();
                } else {
                    $comments = null;
                }
                echo $this->twig->render('front/blog/post/index.html.twig', [
                    'post'      => $post,
                    'comments'  => $comments,
                    'message'   => $this->msg,
                    'maxLength' => $this->configModel->getConfig()['characters'],
                    'categories'    => $categories,
                    'tags'          => $tags,

                ]);
            } else {
                $this->redirect404();
            }
        } else {
            $this->redirect404();
        }
    }
    /**
     * AFFICHAGE NUMERO 2
     */
    public function view2() {
        // define how many results you want per page
        $results_per_page = $this->configModel->getConfig()['ppp'];
        // find out the number of results stored in database
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        // determine number of total pages available
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        // Minimum 1 page
        if ($number_of_pages == 0)
            $number_of_pages = 1;
        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        // check if the page exist
        if ($page > $number_of_pages || $page == 0) {
            $this->redirect404();
        }
        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page-1)*$results_per_page;
        // retrieve selected results from database and display them on page
        $posts = $this->blogModel->getPostsPagination($this_page_first_result, $results_per_page);

        $categories = $this->categoryModel->getAllCategories();

        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('front/blog/index2.html.twig', [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
        ]);
    }
    /**
     * AFFICHAGE NUMERO 3
     */
    public function view3() {
        // define how many results you want per page
        $results_per_page = $this->configModel->getConfig()['ppp'];
        // find out the number of results stored in database
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        // determine number of total pages available
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        // Minimum 1 page
        if ($number_of_pages == 0)
            $number_of_pages = 1;
        // determine which page number visitor is currently on
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        // check if the page exist
        if ($page > $number_of_pages || $page == 0) {
            $this->redirect404();
        }
        // determine the sql LIMIT starting number for the results on the displaying page
        $this_page_first_result = ($page-1)*$results_per_page;
        // retrieve selected results from database and display them on page
        $posts = $this->blogModel->getPostsPagination($this_page_first_result, $results_per_page);

        $categories = $this->categoryModel->getAllCategories();

        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('front/blog/index3.html.twig', [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
        ]);
    }
}
