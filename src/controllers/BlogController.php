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
            $start = ($currentPage-1)*(int)$results_per_page;
            // retrieve selected results from database and display them on page
            $posts = $this->blogModel->getPostsPagination($start, $results_per_page);
        } else {
            $currentPage = $_GET['page'];
        }
        // check if the page exist
        if ($currentPage > $number_of_pages) {
            $this->redirect404();
        }
        // determine the sql LIMIT starting number for the results on the displaying page

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

        if ($currentPage < 1) {
            echo $this->twig->render('front/blog/404.html.twig', $data);
        } elseif ($view == "view1") {
            echo $this->twig->render('front/blog/index.html.twig', $data);
        } elseif ($view == "view2") {
            echo $this->twig->render('front/blog/index2.html.twig', $data);
        } elseif ($view == 'view3') {
            echo $this->twig->render('front/blog/index3.html.twig', $data);
        } else {
            echo $this->twig->render('front/blog/index.html.twig', $data);
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
                    //foreach ($comments as $comment) {
                        //echo '<p style="margin-top:50px"></p><p></p><p></p>';
                        //echo '<pre>';
                        //var_dump($comment['id']);
                        //echo '</pre>';
                        //foreach ($comment['id'] as )

                    //}
                    //$subcomments = $this->commentModel->getCommentById($_GET['id']);

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
                    'links'         => $links,
                    'sublinks'      => $sublinks,
                ]);
            } else {
                $this->redirect404();
            }
        } else {
            $this->redirect404();
        }
    }

    public function getPostsByCategory($category) {
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        if (isset($category)) {
            $results_per_page = $this->configModel->getConfig()['ppp'];
            $populars = $this->blogModel->getMostSeens();
            $categories = $this->categoryModel->getAllCategories();
            $tags = $this->tagModel->getAllTags();
            $links = $this->linkModel->getAllLinks();
            $sublinks = $this->linkModel->getAllSublinks();
            $url = $this->getUrl();
            $posts = $this->blogModel->searchByCategory($category);
            $number = $this->blogModel->countSearchByCategoryRequest($category);
            $number = (int)$number;
            $number_of_pages = ceil($number/$results_per_page);

            if ($number > 0)
            {
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
              $posts = $this->blogModel->getCategoryPagination($category, $start, $results_per_page);

            }
            echo $this->twig->render('front/blog/category/index.html.twig', [
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

    public function getPostsByTag($tag) {
        $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
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

            if ($number > 0)
            {
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
              $posts = $this->blogModel->getTagPagination($tag, $start, $results_per_page);

            }
            echo $this->twig->render('front/blog/tag/index.html.twig', [
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
