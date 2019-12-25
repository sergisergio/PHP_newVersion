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
        if (isset($_GET['page']) AND !empty($_GET['page']) AND ($_GET['page'] > 0 ) AND ($_GET['page'] <= $number_of_pages)) {
            $_GET['page'] = intval($_GET['page']);
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }
        // check if the page exist
        if ($currentPage > $number_of_pages) {
            $this->redirect404();
        }

        // determine the sql LIMIT starting number for the results on the displaying page
        $start = ($currentPage-1)*(int)$results_per_page;
        // retrieve selected results from database and display them on page
        $posts = $this->blogModel->getPostsPagination($start, $results_per_page);
        $populars = $this->blogModel->getMostSeens();
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
            'populars'      => $populars
        ]);
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
                if ($this->isLogged()) {
                    $comments = $this->commentModel->getVerifiedCommentsByPostId($post['id']);

                    $subcomments = $this->commentModel->getCommentById($_GET['id'], $post['id']);

                } else {
                    $comments = null;
                }
                echo $this->twig->render('front/blog/post/index.html.twig', [
                    'post'        => $post,
                    'comments'    => $comments,
                    'message'     => $this->msg,
                    'maxLength'   => $this->configModel->getConfig()['characters'],
                    'categories'  => $categories,
                    'tags'        => $tags,
                    'populars'    => $populars,
                    'subcomments' => $subcomments,
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
        $results_per_page = $this->configModel->getConfig()['ppp'];
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        if (isset($_GET['page']) AND !empty($_GET['page']) AND ($_GET['page'] > 0 ) AND ($_GET['page'] <= $number_of_pages)) {
            $_GET['page'] = intval($_GET['page']);
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }
        if ($currentPage > $number_of_pages) {
            $this->redirect404();
        }
        $start = ($currentPage-1)*(int)$results_per_page;
        $posts = $this->blogModel->getPostsPagination($start, $results_per_page);
        $populars = $this->blogModel->getMostSeens();
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('front/blog/index2.html.twig', [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
            'populars'      => $populars
        ]);
    }
    /**
     * AFFICHAGE NUMERO 3
     */
    public function view3() {
        $results_per_page = $this->configModel->getConfig()['ppp'];
        $number_of_posts = $this->blogModel->getNumberOfPosts();
        $number_of_pages = ceil($number_of_posts/$results_per_page);
        $url = $this->getUrl();
        if (isset($_GET['page']) AND !empty($_GET['page']) AND ($_GET['page'] > 0 ) AND ($_GET['page'] <= $number_of_pages)) {
            $_GET['page'] = intval($_GET['page']);
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }
        /*if ($page > $number_of_pages) {
            $this->redirect404();
        }*/
        $start = ($currentPage-1)*(int)$results_per_page;
        $posts = $this->blogModel->getPostsPagination($start, $results_per_page);
        $populars = $this->blogModel->getMostSeens();
        $categories = $this->categoryModel->getAllCategories();
        $tags = $this->tagModel->getAllTags();
        echo $this->twig->render('front/blog/index3.html.twig', [
            'posts'         => $posts,
            'number'        => $number_of_posts,
            'numberOfPages' => $number_of_pages,
            'categories'    => $categories,
            'tags'          => $tags,
            'url'           => $url,
            'populars'      => $populars
        ]);
    }
}
